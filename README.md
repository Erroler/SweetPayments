![sweetpayments logo](https://i.imgur.com/xvZotOF.png)

> **SweetPayments** is a payment gateway targeted for Counter-Strike: Global Offensive game servers. 
> It allows server owners to monetize their servers by selling premium subscriptions to players. Game server integration is performed via a [sourcemod](https://www.sourcemod.net/) plugin that server owners download through the dashboard¹.
>
> Each subscription can be customized (duration, [sourcemod flags](https://wiki.alliedmods.net/Adding_Admins_(SourceMod)), etc) and can be purchased by players at an online shop accessible by an URL like shop_name.sweetpayments.com
>
> Features **Paypal payments** and **Steam authentication**.
>
> Built with **Laravel 5.8** and **Bootstrap 4**.
> <br>
> <br>
> ¹ <sup>You can view the source code for the sourcemod plugin in the [/storage/plugin/](https://github.com/Erroler/SweetPayments/tree/master/storage/plugin) folder of this repository.</sup>

## Screenshots

Public

- [Landing Page](https://i.imgur.com/7UMvyBF.png)
- [Login screen](https://i.imgur.com/l5Q1rWX.png)
- [Register step 1](https://i.imgur.com/PSNSDio.png)
- [Register step 2](https://i.imgur.com/56nVEdQ.png)

Dashboard
- [Dashboard](https://i.imgur.com/nFODYkL.png)
- [Servers](https://i.imgur.com/9RXdmOK.png)
- [Servers (add)](https://i.imgur.com/Ysfrl8d.png)
- [Subscriptions](https://i.imgur.com/5RIDkHv.png)
- [Subscriptions (edit)](https://i.imgur.com/ObjnVpm.png)
- [Sales](https://i.imgur.com/ReG7ZhE.png)
- [View specific sale](https://i.imgur.com/dsldFiX.png)
- [Withdrawls](https://i.imgur.com/EaipcjG.png)
- [Settings](https://i.imgur.com/HDeTBM6.png)

Example Webshop
- [List Subscriptions for sale](https://i.imgur.com/UDG50ul.png)
- [Buy Subscription step 1](https://i.imgur.com/dlVujP6.png)
- [Buy Subscription step 2](https://i.imgur.com/39Uzors.png)

## Setup

Requires: 

* PHP 7.1+
* Composer

Steps:

> 1. Clone this repository.
> 2. Run **composer install**.
> 3. Rename **.env.example** to **.env**
> 4. Run **php artisan key:generate**
> 5. Open **.env** and fill the following entries: 
> > - RDS_HOSTNAME, RDS_HOSTNAME, RDS_PORT, RDS_USERNAME, RDS_PASSWORD (Database related)
> > - STEAM_API_KEY (Insert your steam API key, you can it from [here](https://steamcommunity.com/dev/apikey))
> 6. Create database tables: **php artisan migrate:refresh**
> 7. Start development server: **php artisan serve**
> 8. Access project at **localhost:8000**

## Disclaimer
> ⚠️ This project is not complete (although it is pretty close to being so) and was never meant to be made public. 
No support will be provided and there will be no further development from my end. 

> This project is being released under the [MIT license](https://choosealicense.com/licenses/mit/) meaning you can use it for commercial purposes.

## What is not implemented:

* Administrator dashboard
* Client ticket support
* Paysafecard integration

## Note

> **SweetPayments is not meant to be self-hosted by clients.** The business plan I had in mind was taking a cut in each payment processed and providing the infrastructure needed (akin to a SaaS model).
