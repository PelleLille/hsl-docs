.. module:: mailfrom

MAIL FROM
=========

The ``MAIL FROM`` script allows verification of the sender.

Variables
---------

========================== ======= ========= ===========
Variable                   Type    Read-only Description
========================== ======= ========= ===========
:ref:`$arguments <v_a4>`   array   yes       Context/hook arguments
:ref:`$connection <v_c4>`  array   yes       Connection/session bound
:ref:`$transaction <v_t4>` array   yes       Transaction bound
$context                   any     no        Connection bound user-defined (default none)
========================== ======= ========= ===========

.. _v_a4:

Arguments
+++++++++

=================== ======= ========================== ===========
Array item          Type    Example                    Description
=================== ======= ========================== ===========
sender              string  "test\@example.org"        Sender address (envelope), lowercase
:ref:`address <a4>` array   ["localpart" => "test"...] Sender address (envelope)
params              array   ["SIZE" => "2048", ... ]   Sender parameters to the envelope address
=================== ======= ========================== ===========

.. _v_c4:

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
:ref:`helo <he4>` array                              HELO information (not always available)
:ref:`tls <tls4>` array                              TLS information (if TLS was started)
:ref:`auth <au4>` array                              AUTH information (not always available)
================= ======= ========================== ===========

.. _he4:

HELO
>>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
verb                 string  "EHLO"                     HELO or EHLO command
host                 string  "mail.example.com"         HELO hostname
==================== ======= ========================== ===========

.. _tls4:

TLS
>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
protocol             string  "TLSv1.3"                  The protocol
cipher               string  "ECDHE-RSA-AES256-SHA384"  The cipher
keysize              number  256                        The keysize
:ref:`peercert <p4>` array                              The peer certificate (if provided by the client)
==================== ======= ========================== ===========

.. _p4:

Peercert
________

==================== ============= ========================== ===========
Array item           Type          Example                    Description
==================== ============= ========================== ===========
x509                 X509Resource                             An X509Resource to be used with the :class:`X509` class
error                number        18                         The peer certificate validation error (see OpenSSLs SSL_get_verify_result(3))
==================== ============= ========================== ===========

.. _au4:

AUTH
>>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
mechanism            string  "PLAIN"                    SASL mechanism (always in uppercase)
username             string  "mailuser"                 SASL username (not always available)
==================== ======= ========================== ===========

.. _v_t4:

Transaction
+++++++++++

========================= ======= ========================== ===========
Array item                Type    Example                    Description
========================= ======= ========================== ===========
id                        string  "18c190a3-93f-47d7-bd..."  ID of the transaction
========================= ======= ========================== ===========

.. _a4:

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

.. function:: Accept([options])

  Accept the `MAIL FROM` command (sender).
  Optionally change the sender accepted, which is written back to ``$transaction``.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **sender** (string or array) Change the sender email address, either as a string or an associative array with a ``localpart`` and ``domain``. The default is the ``address`` argument.
   * **reason** (string) The reason to report. The default is a system generated message.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

  .. note::

  	This function changes the sender for all recipients. To change sender per recipient change the sender in the :doc:`EOD <eod>` context when queuing a recipient.

.. function:: Reject([reason, [options]])

  Reject the `MAIL FROM` command (sender) with a permanent (554) error.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Defer([reason, [options]])

  Defer the `MAIL FROM` command (sender) with a temporary (450) error.

  :param reason: defer message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

On script error
---------------

On script error :func:`Defer` is called.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Accept` is called.
