.. module:: auth

AUTH
====

The AUTH script allows trusted SMTP clients. The SASL mechanisms `LOGIN` and `PLAIN` are implemented in the SMTP engine and will populate the ``$saslusername`` and ``$saslpassword``. If you add support for custom authentication mechanisms you will need to use the ``$saslmechanism``, ``$saslstate`` and ``$saslresponse`` variables to do so.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available for each `AUTH` command.

Connection
^^^^^^^^^^

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$senderip         string  "192.168.1.11"             IP address of the connected client
$senderport       number  41666                      TCP port of connected client
$serverip         string  "10.0.0.1"                 IP address of the server
$serverport       number  25                         TCP port of the server
$serverid         string  "mailserver\:1"            ID of the server
$senderhelo       string  "mail.example.com"         HELO message of sender
$tlsstarted       boolean false                      Whether or not the SMTP session is using TLS
================= ======= ========================== ===========

These are the writable pre-defined variables available.

================= ======= ===========
Variable          Type    Description
================= ======= ===========
$context          any     Connection-bound variable
================= ======= ===========

Transaction
^^^^^^^^^^^

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$transaction      array   ["id" => "18c190a3-93f..." Contains the transaction ID
================= ======= ========================== ===========

Arguments
^^^^^^^^^

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$saslusername     string  "mailuser"                 SASL username
$saslpassword     string  "secret"                   SASL password
$saslmechanism    string  "PLAIN"                    SASL mechanism (always in uppercase)
$saslstate        number  0                          SASL state (incremeted per Reply)
$saslresponse     string  none                       SASL response (not used with LOGIN or PLAIN)
================= ======= ========================== ===========

Functions
---------

.. function:: Accept([options])

  Authorize the login request.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **saslusername** (string) Set the username. The default is ``$saslusername`` (if available).

.. function:: Defer([reason, [options]])

  Defer the login request with a temporary (454) error.

  :param reason: defer message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Reject([reason, [options]])

  Reject the login request.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Reply([reply, [options]])

  Send a reply (334) message. The reply will be base64 encoded before sent to the client. This function is used to implement custom authentication mechanisms.

  :param string reply: the reply message
  :param array options: an options array
  :return: doesn't return, script is terminated
  :updates: ``$saslstate``

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. include:: func_gettls.rst

On script error
---------------

On script error ``Defer()`` is called.

On implicit termination
-----------------------

If not explicitly terminated then ``Reject()`` is called.

Authentication diagram 
----------------------

A flow chart diagram of how custom authentication is implemented::

	             +--------------+
	             | AUTH request |
	             +--------------+
	                    |
	                    |
	                    v
	+---------------------------------------+
	|   $saslstate = 0                      |
	+---------------------------------------+      Accept()      +-------------------+
	| > AUTH $saslmechanism [$saslresponse] | ---- Reject() ---> | AUTH request done |
	+---------------------------------------+      Defer()       +-------------------+
	                    |                             ^
	                    |                             |
	                  Reply() <------------------+    |
	                    |                        |    |
	                    |                        |    |
	                    v                        |    |
	+---------------------------------------+    |    |
	|   $saslstate += 1                     |    |    |
	+---------------------------------------+    |    |
	| > $saslresponse                       | ---+----+
	+---------------------------------------+    |
	                    |                        |
	                    |                        |
	                    +------------------------+
