.. module:: helo

HELO
====

This script is executed on ``HELO`` and ``EHLO``. It allows verification of identification.

Variables
---------

These are the pre-defined variables available.

========================== ======= ========= ===========
Variable                   Type    Read-only Description
========================== ======= ========= ===========
:ref:`$arguments <v_a2>`   array   yes       Context/hook arguments
:ref:`$connection <v_c2>`  array   yes       Connection/session bound
:ref:`$transaction <v_t2>` array   yes       Transaction bound
$context                   any     no        Connection bound user-defined (default none)
========================== ======= ========= ===========

.. _v_a2:

Arguments
+++++++++

================= ======= ========================== ===========
Array item        Type    Example                    Description
================= ======= ========================== ===========
helohost          string  "mail.example.com"         HELO hostname of sender
heloverb          string  "EHLO"                     HELO or EHLO command
extensions        array   ["PIPELINING", "SIZE 1...  The extensions to be sent to the client (if EHLO was issued)
================= ======= ========================== ===========

.. _v_c2:

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
:ref:`tls <tls2>` array                              TLS information (if TLS was started)
saslauthed        boolean true                       Whether or not the SMTP session is authenticated (SASL)
saslusername      string  "mailuser"                 SASL username (not always available)
================= ======= ========================== ===========

.. _tls2:

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

.. _v_t2:

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

  Accept the `HELO` or `EHLO` command. Optionally change the ``helohost`` of the sending client, which is written back to the ``$connection`` variable.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **extensions** (array) SMTP service extensions to announce in EHLO responses.
   * **helohost** (string) Change the HELO hostname for the current connection.
   * **reason** (string) First line of the response. The default is the system hostname.

.. function:: Reject([reason, [options]])

  Reject the `HELO` or `EHLO` command with a permanent (554) error.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Defer([reason, [options]])

  Defer the `HELO` or `EHLO` command with a temporary (450) error.

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
