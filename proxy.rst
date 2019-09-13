.. module:: proxy

Proxy
=====

This advanced and powerful script is can be executed before an SMTP command is sent to the internal state machine of the SMTP server (hence before the command is procesed by the SMTP server). It can be used to built custom SMTP commands or modify the current command line before processing.

Variables
---------

These are the read-only arguments available for each command. Depending on when the proxy script is executed, the different objects may contain different information.

========================== ======= ========= ===========
Variable                   Type    Read-only Description
========================== ======= ========= ===========
:ref:`$arguments <v_a8>`   array   yes       Context/hook arguments
:ref:`$connection <v_c8>`  array   yes       Connection/session bound
:ref:`$transaction <v_t8>` array   yes       Transaction bound
$context                   any     no        Connection bound user-defined (default none)
========================== ======= ========= ===========

.. _v_a8:

Arguments
+++++++++

=================== ======= ========================== ===========
Array item          Type    Example                    Description
=================== ======= ========================== ===========
command             string  "XCLIENT ADDR=1.1.1.1"     The SMTP command line issued
=================== ======= ========================== ===========

.. _v_c8:

Connection
++++++++++

================= ======= ========================== ===========
Array item        Type    Example                    Description
================= ======= ========================== ===========
remoteip          string  "192.168.1.11"             IP address of the connected client
remoteport        number  41666                      TCP port of connected client
remoteptr         string  "mail.example.org"         Reverse DNS (FCrDNS) for remoteip (not always available)
localip           string  "10.0.0.1"                 IP address of the server
localport         number  25                         TCP port of the server
serverid          string  "inbound"                  ID of the server
:ref:`helo <he8>` array                              HELO information (not always available)
:ref:`tls <tls8>` array                              TLS information (if TLS was started)
:ref:`auth <au8>` array                              AUTH information (not always available)
================= ======= ========================== ===========

.. _he8:

HELO
>>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
verb                 string  "EHLO"                     HELO or EHLO command
host                 string  "mail.example.com"         HELO hostname
==================== ======= ========================== ===========

.. _tls8:

TLS
>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
protocol             string  "TLSv1.3"                  The protocol
cipher               string  "ECDHE-RSA-AES256-SHA384"  The cipher
keysize              number  256                        The keysize
:ref:`peercert <p8>` array                              The peer certificate (if provided by the client)
==================== ======= ========================== ===========

.. _p8:

Peercert
________

==================== ============= ========================== ===========
Array item           Type          Example                    Description
==================== ============= ========================== ===========
x509                 X509Resource                             An X509Resource to be used with the :class:`X509` class
error                number        18                         The peer certificate validation error (see OpenSSLs SSL_get_verify_result(3))
==================== ============= ========================== ===========

.. _au8:

AUTH
>>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
mechanism            string  "PLAIN"                    SASL mechanism (always in uppercase)
username             string  "mailuser"                 SASL username (not always available)
==================== ======= ========================== ===========

.. _v_t8:

Transaction
+++++++++++

========================= ======= ================================ ===========
Array item                Type    Example                          Description
========================= ======= ================================ ===========
id                        string  "18c190a3-93f-47d7-bd..."        ID of the transaction
sender                    string  "test\@example.org"              Sender address (envelope), lowercase
:ref:`senderaddress <a8>` array   ["localpart" => "examp...]       Sender address (envelope)
senderparams              array   ["SIZE" => "2048", ... ]         Sender parameters to the envelope address
recipients                array   [:ref:`recipient <v_t_r8>`, ...] List of all accepted recipients (envelope), in order of scanning
========================= ======= ================================ ===========

.. _v_t_r8:

Recipient
>>>>>>>>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
recipient            string  "test\@example.com"        Recipient address (envelope), lowercase
:ref:`address <a8>`  array   ["localpart" => "examp...] Recipient address (envelope)
params               array   ["NOTIFY" => "NEVER", .. ] Recipient parameters to the envelope address
transportid          string  "inbound"                  Transport ID for recipient
==================== ======= ========================== ===========

.. _a8:

Address
>>>>>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
localpart            string  "test"                     Local part of address
domain               string  "example.org"              Domain part of address
==================== ======= ========================== ===========

Functions
---------

.. function:: Pass([options])

  Pass the command to the SMTP server's state machine.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **command** (string) Change the SMTP command.
   * **next** (boolean) Request to get the next command as well. The default is ``false``.

.. function:: Reply([reason, [options]])

  Send a reply to the client (The default is code 250). The command is not passed to the SMTP server's state machine.

  :param reason: the message to reply
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.
   * **next** (boolean) Request to get the next command as well. The default is ``false``.

On script error
---------------

On script error :func:`Reply` is called with a generic 421 response.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Pass` is called.
