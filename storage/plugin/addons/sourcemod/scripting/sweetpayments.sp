#include <sourcemod>
#include <ripext>

#define API_URL "https://sweetpayments.net/api"
#define FLAG_LETTERS_SIZE 26

public Plugin:myinfo = 
{
	name = "SweetPayments.net",
	author = "",
	description = "A payment gateway for selling subscriptions.",
	version = "1.0",
	url = "https://sweetpayments.net"
}

Handle h_private_key;
char private_key[32];
AdminFlag g_FlagLetters[FLAG_LETTERS_SIZE];

public OnPluginStart()
{
	h_private_key = CreateConVar("sm_sweetpayments_key", "", "Private key for linking your gameservers to SweetPayments.net");
	AutoExecConfig(true, "sweetpayments");
	HookConVarChange(h_private_key, OnConVarChange);
	g_FlagLetters = CreateFlagLetters();

	CreateTimer(45.0, Timer_SendServerStatus, _, TIMER_REPEAT);
}

public OnConVarChange(Handle:convar, const String:oldValue[], const String:newValue[])
{
	GetConVarString(h_private_key, private_key, sizeof(private_key));
}

public OnConfigsExecuted()
{
	GetConVarString(h_private_key, private_key, sizeof(private_key));
}

public Action Timer_SendServerStatus(Handle timer)
{
	char buffer[128];
	char buffer2[128];
	char buffer3[128];
	// Create HTTPClient.
	HTTPClient httpClient;
	httpClient = new HTTPClient(API_URL);
	httpClient.SetHeader("key", private_key);
	httpClient.SetHeader("action", "server_update");
	IntToString(GetMaxHumanPlayers(), buffer, sizeof(buffer));
	httpClient.SetHeader("maxplayers", buffer);
	IntToString(GetRealClientCount(), buffer2, sizeof(buffer2));
	httpClient.SetHeader("currentplayers", buffer2);
	GetCurrentMap(buffer3, sizeof(buffer3));
	httpClient.SetHeader("currentmap", buffer3);
	httpClient.SetHeader("serveraddress", GetServerIP());
	httpClient.SetHeader("servername", GetServerName());
	httpClient.Get("", OnServerUpdateResponse);
 
	return Plugin_Continue;
}


public void OnServerUpdateResponse(HTTPResponse response, int client)
{
	if (response.Status != HTTPStatus_OK)
		LogError("Failed to update server status on sweetpayments.net");
}

public void OnRebuildAdminCache(AdminCachePart part)
{
	if (part == AdminCache_Admins) 
	{
		for (int i = 1; i < MAXPLAYERS; i++)
		{
			if(IsClientConnected(i) && IsClientInGame(i)){
				RetrieveClientPermissions(i);
			}
		}
	}
}

public OnClientPostAdminCheck(client) 
{
	RetrieveClientPermissions(client)
}

void RetrieveClientPermissions(client)
{
	if(IsFakeClient(client)) return;

	// Get client SteamID64.
	char steamid64[32];
	GetClientAuthId(client, AuthId_SteamID64, steamid64, sizeof(steamid64), true);

	// Create HTTPClient.
	HTTPClient httpClient;
	httpClient = new HTTPClient(API_URL);
	httpClient.SetHeader("key", private_key);
	httpClient.SetHeader("action", "player_info");
	httpClient.SetHeader("steamid64", steamid64);
	httpClient.Get("", OnClientInfoReceived, client);
}

public void OnClientInfoReceived(HTTPResponse response, int client)
{
	if (response.Status != HTTPStatus_OK || response.Data == null) {
		if(response.Status == HTTPStatus_Unauthorized)
			LogError("Failed to retrieve permissions for a client - invalid private key. Please download the plugin again from SweetPayments.net");
		else
			LogError("Failed to retrieve permissions for a client. HTTP Status code %d", response.Status);
		return;
	}

	JSONObject data = view_as<JSONObject>(response.Data);


	char flags[50];
	data.GetString("flags", flags, sizeof(flags));
	int immunity = data.GetInt("immunity");
	data.GetString("immunity", flags, sizeof(flags));
	data.GetString("flags", flags, sizeof(flags));
	//
	if(strlen(flags) > 0 || immunity > 0)
		GiveFlags(client, flags, immunity);
}

GiveFlags(client, char[] flags, immunity){ // Taken from sourcebans
	if(!IsClientConnected(client))	return;
	AdminId curAdm = INVALID_ADMIN_ID;
	char SIdentity[40];
	GetClientAuthId(client, AuthId_Steam2, SIdentity, sizeof(SIdentity), true);
	if( (curAdm = GetUserAdmin(client)) == INVALID_ADMIN_ID){
		curAdm = CreateAdmin("");
		// That should never happen!
		if(!BindAdminIdentity(curAdm, "steam", SIdentity))
		{
			RemoveAdmin(curAdm);
			return;
		}
	}
	
	for (int i = 0; i < 30; ++i)
	{
		if (flags[i] < 'a' || flags[i] > 'z')
			continue;
			
		if (g_FlagLetters[flags[i] - 'a'] < Admin_Reservation)
			continue;
			
		SetAdminFlag(curAdm, g_FlagLetters[flags[i] - 'a'], true);
	}
	SetAdminImmunityLevel(curAdm, immunity);
	SetUserAdmin(client, curAdm, true);
}

stock AdminFlag CreateFlagLetters()
{
	AdminFlag FlagLetters[FLAG_LETTERS_SIZE];

	FlagLetters['a'-'a'] = Admin_Reservation;
	FlagLetters['b'-'a'] = Admin_Generic;
	FlagLetters['c'-'a'] = Admin_Kick;
	FlagLetters['d'-'a'] = Admin_Ban;
	FlagLetters['e'-'a'] = Admin_Unban;
	FlagLetters['f'-'a'] = Admin_Slay;
	FlagLetters['g'-'a'] = Admin_Changemap;
	FlagLetters['h'-'a'] = Admin_Convars;
	FlagLetters['i'-'a'] = Admin_Config;
	FlagLetters['j'-'a'] = Admin_Chat;
	FlagLetters['k'-'a'] = Admin_Vote;
	FlagLetters['l'-'a'] = Admin_Password;
	FlagLetters['m'-'a'] = Admin_RCON;
	FlagLetters['n'-'a'] = Admin_Cheats;
	FlagLetters['o'-'a'] = Admin_Custom1;
	FlagLetters['p'-'a'] = Admin_Custom2;
	FlagLetters['q'-'a'] = Admin_Custom3;
	FlagLetters['r'-'a'] = Admin_Custom4;
	FlagLetters['s'-'a'] = Admin_Custom5;
	FlagLetters['t'-'a'] = Admin_Custom6;
	FlagLetters['z'-'a'] = Admin_Root;

	return FlagLetters;
}

stock GetRealClientCount() {
    new iClients = 0;

    for (new i = 1; i <= MaxClients; i++) {
        if (IsClientInGame(i) && !IsFakeClient(i)) {
            iClients++;
        }
    }

    return iClients;
}  

char[] GetServerIP() {
	int pieces[4];
	int longip = GetConVarInt(FindConVar("hostip"));
	int port = GetConVarInt(FindConVar("hostport"));
	pieces[0] = (longip >> 24) & 0x000000FF;
	pieces[1] = (longip >> 16) & 0x000000FF;
	pieces[2] = (longip >> 8) & 0x000000FF;
	pieces[3] = longip & 0x000000FF;

	char NetIP[40];
	Format(NetIP, sizeof(NetIP), "%d.%d.%d.%d:%d", pieces[0], pieces[1], pieces[2], pieces[3], port);  
	return NetIP;
}

char[] GetServerName() {
	char sBuffer[256];
	GetConVarString(FindConVar("hostname"), sBuffer,sizeof(sBuffer));
	return sBuffer;
}