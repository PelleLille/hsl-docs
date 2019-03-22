.. module:: rcptto

RCPT TO
=======

The ``RCPT TO`` script allows verification of recipients.

Variables
---------

========================== ======= ========= ===========
Variable                   Type    Read-only Description
========================== ======= ========= ===========
:ref:`$arguments <v_a5>`   array   yes       Context/hook arguments
:ref:`$connection <v_c5>`  array   yes       Connection/session bound
:ref:`$transaction <v_t5>` array   yes       Transaction bound
$context                   any     no        Connection bound user-defined (default none)
========================== ======= ========= ===========

.. _v_a5:

Arguments
+++++++++

=================== ======= ========================== ===========
Array item          Type    Example                    Description
=================== ======= ========================== ===========
recipient           string  "test\@example.com"        Email address of recipient (envelope), lowercase
recipientlocalpart  string  "test"                     Local part of recipient's address (envelope)
recipientdomain     string  "example.com"              Domain part of recipient's address (envelope)
recipientparams     array   ["NOTIFY" => "NEVER", .. ] Recipient parameters to the envelope address
transportid         string  "inbound"                  ID of the transport profile to be used
=================== ======= ========================== ===========

.. _v_c5:

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
:ref:`tls <tls5>` array                              TLS information (if TLS was started)
:ref:`auth <au5>` array                              AUTH information (not always available)
================= ======= ========================== ===========

.. _tls5:

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

.. _au5:

AUTH
>>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
mechanism            string  "PLAIN"                    SASL mechanism (always in uppercase)
username             string  "mailuser"                 SASL username (not always available)
==================== ======= ========================== ===========

.. _v_t5:

Transaction
+++++++++++

========================= ======= ================================ ===========
Array item                Type    Example                          Description
========================= ======= ================================ ===========
id                        string  "18c190a3-93f-47d7-bd..."        ID of the transaction
sender                    string  "test\@example.org"              Email address of sender (envelope), lowercase
senderlocalpart           string  "test"                           Local part of sender's address (envelope)
senderdomain              string  "example.org"                    Domain part of sender's address (envelope)
senderparams              array   ["SIZE" => "2048", ... ]         Sender parameters to the envelope address
recipients                array   [:ref:`recipient <v_t_r5>`, ...] List of all accepted recipients (envelope), in order of scanning
========================= ======= ================================ ===========

.. _v_t_r5:

Recipient
>>>>>>>>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
recipient            string  "test\@example.com"        Recipient address, lowercase
recipientlocalpart   string  "test"                     Local part of recipient address
recipientdomain      string  "example.com"              Domain part of recipient address
recipientparams      array   ["NOTIFY" => "NEVER", .. ] Recipient parameters to the envelope address
transportid          string  "inbound"                  Transport ID for recipient
==================== ======= ========================== ===========

Functions
---------

.. function:: Accept([options])

  Accept the `RCPT TO` command (recipient).
  Optionally change the recipient accepted and its transport, which is written back to ``$transaction`` for subsequent executions.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **recipient** (string or array) Change the recipient email address, either as a string or a tuple with localpart and domain. The default is the ``recipientlocalpart`` argument `at` the ``recipientdomain`` argument.
   * **transportid** (string) Change the transport ID. The default is the ``transportid`` argument.
   * **reason** (string) The reason to report. The default is a system generated message.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Reject([reason, [options]])

  Reject the `RCPT TO` command (recipient) with a permanent (554) error.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Defer([reason, [options]])

  Defer the `RCPT TO` command (recipient) with a temporary (450) error.

  :param reason: defer message with reason
  :type reason: string or array
  :param array options: an options array
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

If not explicitly terminated then :func:`Accept` is called.
