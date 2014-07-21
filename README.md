user_servervars
===============

Fork of http://apps.owncloud.com/content/show.php/user_servervars?content=158863

INTRODUCTION
============

This App provides authentication and provisioning support based on HTTP server
environment variables and, concomitantly, on HTTP authentication if needed.

ACKNOWLEDGMENT
==============

This App heavilly and shamelessly dwells on the user_saml App by Sixto Martin
(Yaco Sistemas // CONFIA). Funny and / or foolish code parts are my own
responsibility.

INSTALLATION
============

PREVIOUS DEPENDENCE
-------------------

This App has no library dependence. However it requires proper HTTP server
configuration and knowledge of parts of its configuration.

STEPS
-----

1. Copy the 'user_servervars' folder inside the ownCloud's apps folder and give
   to apache server read access privileges on whole the folder.
2. Access to ownCloud web with an user with admin privileges.
3. Access to the Applications panel and enable the SERVERVARS app.
4. Access to the Administration panel and configure the SERVERVARS app.
5. Login through this App is handled at the URL /?app=user_servervars ; it may
   be suitable to add a redirection from root page to this URL.

EXTRA INFO
==========

* The "External URL to redirect to for authentication" parameter redirects user
* if he cannot be identified.

* The "External URL to redirect to on logout" parameter redirects user after
* his session on owncloud has been cleared. This may be required on some SSO
* platforms to further dispose of sessions with the HTTP server itself.

* If you enable the "Autocreate user after ServerVars login" option, then if an
* user does not exist, he will be created. If this option is disabled and the
* user does not existed then the user will be not allowed to log in ownCloud.

* If you enable the "Update user data" option, when an existing user enters,
* his displayName, email and groups will be updated.

  By default the SERVERVARS App will unlink all the groups from a user and will
  provide the group defined at the "Primary group variable" attribute. If the
  groupMapping is not defined the value of the "Default group (...)" field will
  be used instead. If both are undefined, then the user will be set with no
  groups.  But if you configure the "Groups protected (...)" field, those
  groups will not be unlinked from the user.

The mapping section of configuration map user's attributes with PHP code which
should evaluate to actual user specifics values. The intended functionnality is
to thus design global variables which expand to the actual values, but more
general PHP code may be added here.

One may argue on security risks associated with this approach. Note that only
an admin user has access to the App parameters and may change the values to
hazardous ones. Compare with the ability for an admin user to install 3rd-party
Apps. Anyway, one may disable modifications on variables through the definition
of SERVERVARS_RO_BINDINGS in 'settings.php'. This can be done after correct
configuration of mapping with the config page.

Mappings are:
1. Login name: the unique identifier for the user
2. Display name: the human-compatible identifier for the user
3. Email variable: the user's mail
4. Primary group: the user's group or groups (values separated by any count of
commas and spaces)

* If you want to redirect to any specific app after force the login you can set
* the url param linktoapp. Also you can pass extra args to build the target url
* using the param linktoargs (the value must be urlencoded).
  Ex. ?app=user_servervars&linktoapp=files&linktoargs=file%3d%2ftest%2ftest_file.txt%26getfile%3ddownload.php
      ?app=user_servervars&linktoapp=files&linktoargs=dir%3d%2ftest

NOTES
=====

No notes so far.

