.. module:: eodonce

Per message
===========

The per-message end-of-DATA script is executed once, when the message is fully received (but not yet accepted).
To relay the message for all recipients, call :func:`EODMailMessage.queue` for each ``$transaction["recipients"]`` and then :func:`Accept`.

Variables
---------

These are the read-only pre-defined variables available.

========================== ======= ========= ===========
Variable                   Type    Read-only Description
========================== ======= ========= ===========
:ref:`$arguments <v_a6>`   array   yes       Context/hook arguments
:ref:`$connection <v_c6>`  array   yes       Connection/session bound
:ref:`$transaction <v_t6>` array   yes       Transaction bound
$context                   any     no        Connection bound user-defined (default none)
========================== ======= ========= ===========

.. _v_a6:

Arguments
+++++++++

================= ================================= ========================== ===========
Array item        Type                              Example                    Description
================= ================================= ========================== ===========
mail              :cpp:class:`EODMailMessage`                                  An instance of the mail message
================= ================================= ========================== ===========

.. _v_c6:

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
:ref:`helo <he6>` array                              HELO information (not always available)
:ref:`tls <tls6>` array                              TLS information (if TLS was started)
:ref:`auth <au6>` array                              AUTH information (not always available)
================= ======= ========================== ===========

.. _he6:

HELO
>>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
verb                 string  "EHLO"                     HELO or EHLO command
host                 string  "mail.example.com"         HELO hostname
==================== ======= ========================== ===========

.. _tls6:

TLS
>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
protocol             string  "TLSv1.3"                  The protocol
cipher               string  "ECDHE-RSA-AES256-SHA384"  The cipher
keysize              number  256                        The keysize
:ref:`peercert <p6>` array                              The peer certificate (if provided by the client)
==================== ======= ========================== ===========

.. _p6:

Peercert
________

==================== ============= ========================== ===========
Array item           Type          Example                    Description
==================== ============= ========================== ===========
x509                 X509Resource                             An X509Resource to be used with the :class:`X509` class
error                number        18                         The peer certificate validation error (see OpenSSLs SSL_get_verify_result(3))
==================== ============= ========================== ===========

.. _au6:

AUTH
>>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
mechanism            string  "PLAIN"                    SASL mechanism (always in uppercase)
username             string  "mailuser"                 SASL username (not always available)
==================== ======= ========================== ===========

.. _v_t6:

Transaction
+++++++++++

========================= ======= ================================ ===========
Array item                Type    Example                          Description
========================= ======= ================================ ===========
id                        string  "18c190a3-93f-47d7-bd..."        ID of the transaction
sender                    string  "test\@example.org"              Sender address (envelope), lowercase
:ref:`senderaddress <a6>` array   ["localpart" => "test"...]       Sender address (envelope)
senderparams              array   ["SIZE" => "2048", ... ]         Sender parameters to the envelope address
recipients                array   [:ref:`recipient <v_t_r6>`, ...] List of all accepted recipients (envelope), in order of scanning
========================= ======= ================================ ===========

.. _v_t_r6:

Recipient
>>>>>>>>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
recipient            string  "test\@example.com"        Recipient address (envelope), lowercase
:ref:`address <a6>`  array   ["localpart" => "test"...] Recipient address (envelope)
recipientparams      array   ["NOTIFY" => "NEVER", .. ] Recipient parameters to the envelope address
transportid          string  "inbound"                  Transport ID for recipient
==================== ======= ========================== ===========

.. _a6:

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

* **Actions** :func:`Accept` :func:`Reject` :func:`Defer`
* **Logging** :func:`History`
* **DATA, MIME and attachments** :cpp:class:`EODMailMessage` :cpp:class:`MIMEPart`
* **Embedded scanning** :func:`ScanDMARC` :func:`ScanDLP` :func:`ScanRPD` :func:`ScanSA` :func:`ScanKAV` :func:`ScanCLAM`
* **Miscellaneous** :func:`GetMailQueueMetric`

Actions
+++++++

.. function:: Accept([options])

  Accept the `DATA` command (mail data).

  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **reason** (string) The reason to report. The default is a system generated message.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Reject([reason, [options]])

  Reject (550) a message. If `reason` is an array or contains `\\n` it will be split into a multiline response.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Defer([reason, [options]])

  Defer (421) a message. If `reason` is an array or contains `\\n` it will be split into a multiline response.

  :param reason: defer message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

Logging
+++++++

.. function:: History(action, recipient, [options])

  Add an entry to the history database table.
  This function is only available in the full system distribution (virtual machine) package.
  For long-term logging in high volume systems, remote logging to an external database such as Elasticsearch is recommended.

  :param string action: the logged action; either of `REJECT`, `DELETE`, `DELIVER`, `DEFER` or `ERROR`
  :param recipient: the recipient email address, either as a string or an associative array with a ``localpart`` and ``domain``
  :type recipient: string or array
  :param array options: an options array
  :return: true (or none)
  :rtype: boolean or none

  The following options are available in the options array.

   * **sender** (string or array) the sender email address, either as a string or an associative array with a ``localpart`` and ``domain``. The default is ``$transaction["senderaddress"]``
   * **metadata** (array) add metadata to the history entry, as a key-value pair array of strings
   * **transportid** (string) the transport profile ID
   * **reason** (string) reason message

DATA, MIME and attachments
++++++++++++++++++++++++++

.. _mailmessage:

.. cpp:class:: EODMailMessage : MailMessage

In the EOD once context the :cpp:class:`MailMessage` class has been extended with one additional function; :func:`EODMailMessage.queue`.

  .. function:: EODMailMessage.queue(sender, recipient, transportid, [options])

    Queue the message.

    :param sender: the sender email address, either as a string or an associative array with a ``localpart`` and ``domain``
    :type sender: string or array
    :param recipient: the recipient email address, either as a string or an associative array with a ``localpart`` and ``domain``
    :type recipient: string or array
    :param string transportid: the transport profile ID
    :param array options: an options array
    :return: an id object (with ``transaction`` and ``queue``)
    :rtype: array

    The following options are available in the options array.

     * **metadata** (array) Add metadata to the queued message, as a key-value pair array of strings.
     * **hold** (boolean) Put the message in the hold (inactive) queue. The default is ``false``.
     * **jobid** (string) Assign a jobid the message.
     * **delay** (number) Delay the first delivery attempt, in seconds. The default is ``0``.

.. include:: func_eod.rst

On script error
---------------

On script error :func:`Defer` is called.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Defer` is called and no recipients will be queued.
