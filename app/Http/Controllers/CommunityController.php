<?php

namespace App\Http\Controllers;

use \Steam;
use Exception;
use ErrorException;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\DomCrawler\Crawler;

class CommunityController extends Controller
{
    /**
     * Displays the community selection screen. Users must select a gaming community after the first login in the platform.
     */
    public function view_register_community_step1()
    {
        return view('pages.dashboard.choose_community_step1');
    }
    
    /**
     * Form submission handler for first step of registering the community.
     */
    public function submit_register_community_step1(Request $request)
    {
        // Validate request and get community_url.
        $request->validate([
            'community_url' => "required|regex:".config('settings.STEAM_GROUP_REGEX')
            ],[
            'community_url.required' => 'You must insert a link to the steam group of the gaming community you own.',
            'community_url.regex' => 'The url you entered is not valid.',
        ]);
        $community_url = $request->get('community_url');
        try {
            $group = $this->getSteamGroup($community_url);
        }
        catch(Exception $e) {
            if($e->getMessage() === "The current node list is empty.")
                $error_message = 'Steam group doesn\'t exist.';
            else
                $error_message = $e->getMessage();

            return redirect()->back()->withInput()->withErrors(['community_url' => $error_message]);
        }

        if(Community::where('group_id', $group['group_id'])->first() != NULL){
            return redirect()->back()->withInput()->withErrors(['community_url' => 'That steam group has already been registered.']);
        }

        Session::put('community', $group);

        return redirect()->route('community.select.step2');
    }

    public function view_register_community_step2() 
    {
        if (!Session::has('community'))
            return redirect()->route('community.select.step1');

        $community = Session::get('community');

        return view('pages.dashboard.choose_community_step2', compact('community'));
    }

    public function submit_register_community_step2() 
    {
        if (!Session::has('community'))
            return redirect()->route('community.select.step1');

        $group = Session::get('community');

        // Verify if shop url is unique.        
        if (Community::where('small_name', $group['small_name'])->first() != NULL) {
            $group['small_name'] = $group['small_name'].rand(1000, 500000);
        }

        if(Community::where('group_id', $group['group_id'])->first() != NULL){
            return redirect()->route('community.select.step1')->withErrors(['community_url' => 'That steam group has already been registered.']);
        }

        $group['user_id'] = Auth::user()->id;
        Community::create($group);
            
        return redirect()->route('panel.dashboard');
    }


    public function getSteamGroup($community_url)
    {
        // Extract short community url.
        $URL_REGEX = config('settings.STEAM_GROUP_REGEX');
        preg_match($URL_REGEX, $community_url, $regex_result);
        $community_abbrv_name = $regex_result[1];

        // Visit URL and validate html status code.
        $client = new \GuzzleHttp\Client;
        $res = $client->get($community_url);

        if ($res->getStatusCode() != 200) {
            throw new Exception('Could not open URL inserted.');
        }

        // Start crawler.
        $html_body = (string) $res->getBody();
        $crawler = (new Crawler($html_body))->filter('.pagecontent')->first();

        // Get steam group name.
        $community_full_name = $crawler->filter('.grouppage_header_name')->first()->text();
        $community_full_name = trim($community_full_name, "\t\n\r\v");
        $community_full_name = explode("\t", $community_full_name)[0];

        // Get administrators of group.
        $admin_section = $crawler->filter('.member_sectionheader')->reduce(function (Crawler $node, $i) {
            try {
                $icon_url = $node->children()->first()->attr('src');
                return (strpos($icon_url, 'Admin') !== false);
            }
            catch(Exception $e) {
                return false;
            }
        })->first();
        $sections_after = $admin_section->nextAll();
        $found = false;
        foreach ($sections_after as $node) {
            $class = $node->getAttribute('class');
            if($class === "member_sectionheader")
                break;
            if($class !== ""){
                $profile_url = $node->childNodes[1]->getAttribute('href').'/';
                if($profile_url === Auth::user()->profile_url) {
                    $found = true;
                    break;
                }
            }
        }
        if(!$found) {
            throw new Exception('You must be an administrator of the steam group.');
        }

        // Retrieve extra group details using the Steam facade provided by /syntaxerrors/Steam
        $group_summary = Steam::group()->GetGroupSummary($community_abbrv_name);
        $group_id = $group_summary->groupID64;
        $avatar = $group_summary->groupDetails->avatarFullUrl;
        $member_count = $group_summary->memberDetails->count;

        return [
            'small_name' => $community_abbrv_name,
            'full_name' => $community_full_name,
            'group_id' => $group_id,
            'avatar' => $avatar,
            'members' => $member_count
        ];
    }
}
