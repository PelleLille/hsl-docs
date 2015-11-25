.. module:: api

API
===

The API script is executed for each `SOAP <http://wiki.halon.se/SOAP>`_ API call, to verify the authentication credentials and enforce permissions. All communication to the system goes through the API; even the (jailed) web interface and console/terminal interface. This script can be used to grant limited permissions for external services such as the `end-user interface <http://wiki.halon.se/End-user>`_, but also to implement external authentication (such as LDAP) for access to the local system.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available each time a SOAP function is called.

=========== ======= =============== ===========
Variable    Type    Example         Description
=========== ======= =============== ===========
$clientip   string  "192.168.1.12"  The IP address of the client
$username   string  "loginuser"     Username
$password   string  "secret"        Password
$service    string  "webui"         Eg. "ssh", "ftp"...
$soapcall   string  "configKeySet"  The SOAP function that is executed
$soapargs   array   []              The SOAP function arguments (not always available)
=========== ======= =============== ===========

Functions
---------

.. function:: Authenticate([options])

  Authorizes the API call.

  :param array options: options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **fullname** (string) Corresponds to the "Full Name" property of configuration users. The default is ``$username``.
   * **accesslevel** (string) The access level string, such as "r" for read-only. The default is no accesslevel restrictions.

.. function:: Deny([message])

  Denies the API call.

  :param string message: reason for denying. The default is `Unauthorized`.
  :return: doesn't return, script is terminated

On script error
---------------

On script error ``Deny()`` is called.
