Kayako Joomla Integration
=======================

This library is maintained by Kayako.

Overview
=======================

Kayako module Joomla integration for Kayako version 4. This module can be used on a Joomla based website for basic functionality of ticket creation and status checking.

User only needs to log in to the website and he will be able to create/view/update tickets corresponding to the email id by which he has logged in.

Features
=======================

* Users can create tickets from Joomla based website.
* Users can view tickets in Native Joomla UI.
* Users can reply or update tickets

Supported versions
=======================

* Kayako: v4.51.1891 and above
* Joomla: Joomla 3.0.0 and above

Installation Steps
=======================

1. Download and extract the Kayako-Joomla integration.
2. You will find mod_kayako under src/ , place it in joomla_installation/modules/. 
3. Locate the new kayako module from Extensions->Discover and install it.
4. Now Open the Joomla Admin panel and go to Module Manager. (You can find the Module Manager under Extension)
5. Click on New link and select mod_kayako module. 
6. Fill all the fields in the basic options tab like API Key, Secret Key and API URL.
7. Now select the status as 'Published' and save it.
8. After publishing, Create Ticket and View Tickets option will be displayed at front end of Joomla. 