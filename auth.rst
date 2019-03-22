.. module:: auth

AUTH
====

The AUTH script allows trusted SMTP clients. The SASL mechanisms `LOGIN` and `PLAIN` are implemented in the SMTP engine and will populate the ``username`` and ``password`` argument. If you add support for custom authentication mechanisms you will need to use the ``mechanism``, ``state`` and ``response`` arguments instead.

Variables
---------

These are the pre-defined variables available.

========================== ======= ========= ===========
Variable                   Type    Read-only Description
========================== ======= ========= ===========
:ref:`$arguments <v_a3>`   array   yes       Context/hook arguments
:ref:`$connection <v_c3>`  array   yes       Connection/session bound
:ref:`$transaction <v_t3>` array   yes       Transaction bound
$context                   any     no        Connection bound user-defined (default none)
========================== ======= ========= ===========

.. _v_a3:

Arguments
+++++++++

============= ======= ========================== ===========
Array item    Type    Example                    Description
============= ======= ========================== ===========
username      string  "mailuser"                 SASL username (only available with LOGIN or PLAIN)
password      string  "secret"                   SASL password (only available with LOGIN or PLAIN)
mechanism     string  "PLAIN"                    SASL mechanism (always in uppercase)
state         number  0                          SASL state, incremeted per Reply (not available with LOGIN or PLAIN)
response      string  none                       SASL response (not available with LOGIN or PLAIN)
============= ======= ========================== ===========

.. _v_c3:

Connection
++++++++++

================= ======= ========================== ===========
Array item        Type    Example                    Description
================= ======= ========================== ===========
remoteip          string  "192.168.1.11"             IP address of the connected client
remoteport        number  41666                      TCP port of connected client
localip           string  "10.0.0.1"                 IP address of the server
localport         number  25                         TCP port of the server
serverid          string  "inbound"                  ID of the server
helohost          string  "mail.example.com"         HELO hostname of sender (not always available)
:ref:`tls <tls3>` array                              TLS information (if TLS was started)
================= ======= ========================== ===========

.. _tls3:

TLS
>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
protocol             string  "TLSv1.3"                  The protocol
cipher               string  "ECDHE-RSA-AES256-SHA384"  The cipher
keysize              number  256                        The keysize
peercert             x509                               The peer certificate (if provided by the client)
peercerterror        number  18                         The peer certificate validation error (see OpenSSLs SSL_get_verify_result(3))
==================== ======= ========================== ===========

.. _v_t3:

Transaction
+++++++++++

========================= ======= ========================== ===========
Array item                Type    Example                    Description
========================= ======= ========================== ===========
id                        string  "18c190a3-93f-47d7-bd..."  ID of the transaction
========================= ======= ========================== ===========

Functions
---------

.. function:: Accept([options])

  Authorize the login request.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **username** (string) Set or change the username. The default is the ``username`` argument (if available).
   * **reason** (string) The reason to report. The default is a system generated message.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Reject([reason, [options]])

  Reject the login request with a permanent (535) error.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Defer([reason, [options]])

  Defer the login request with a temporary (454) error.

  :param reason: defer message with reason
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
  :increments: ``state`` argument
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: GetMailQueueMetric([options])

  Return metric information about the mail queue, it can be used to enforce quotas.

  :param array options: options array
  :rtype: number

.. include:: func_getmailqueuemetric.rst

On script error
---------------

On script error :func:`Defer` is called.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Reject` is called.

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
	|   state = 0                           |
	+---------------------------------------+      Accept()      +-------------------+
	| > AUTH mechanism [response]           | ---- Reject() ---> | AUTH request done |
	+---------------------------------------+      Defer()       +-------------------+
	                    |                             ^
	                    |                             |
	                  Reply() <------------------+    |
	                    |                        |    |
	                    |                        |    |
	                    v                        |    |
	+---------------------------------------+    |    |
	|   state += 1                          |    |    |
	+---------------------------------------+    |    |
	| > response                            | ---+----+
	+---------------------------------------+    |
	                    |                        |
	                    |                        |
	                    +------------------------+
