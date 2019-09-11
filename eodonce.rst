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
* **DATA, MIME and attachments** :cpp:class:`MailMessage` :cpp:class:`MIMEPart`
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

In the EOD once context the :cpp:class:`MailMessage` class has been extended and has two additional functions; :func:`EODMailMessage.queue` and :func:`EODMailMessage.send`.

  .. function:: EODMailMessage.queue(sender, recipient, transportid, [options])

    Queue the message.

    :param sender: the sender email address, either as a string or an associative array with a ``localpart`` and ``domain``
    :type sender: string or array
    :param recipient: the recipient email address, either as a string or an associative array with a ``localpart`` and ``domain``
    :type recipient: string or array
    :param string transportid: the transport profile ID
    :param array options: an options array
    :rtype: none

    The following options are available in the options array.

     * **metadata** (array) Add metadata to the queued message, as a key-value pair array of strings.
     * **hold** (boolean) Put the message in the hold (inactive) queue. The default is ``false``.
     * **jobid** (string) Assign a jobid the message.
     * **delay** (number) Delay the first delivery attempt, in seconds. The default is ``0``.

  .. function:: EODMailMessage.send(sender, recipients, server)

    Try to send the message to the server.

    :param sender: the sender (`MAIL FROM`), an address object
    :type sender: string or array
    :param recipients: the recipient (`RCPT TO`), an array of address objects
    :type recipients: array of (string or array)
    :param server: array with server settings or transport profile ID
    :type server: string or array
    :return: associative array containing the result or an error
    :rtype: array

    The address parameters should be either a string or an associative array with a ``localpart`` and ``domain`` and optionally a ``params`` field as an key-values array (to be sent in the `MAIL FROM` or `RCPT TO` command).

    .. code-block:: hsl

      $response = $message->deliver(
          ["localpart" => "nick", "domain" => "example.org"],
          [
              ["localpart" => "chris", "domain" => "example.com", "params" => ["NOTIFY" => "DELAY"]],
              ["localpart" => "charlie", "domain" => "example.com"],
          ],
          ["host" => "10.2.0.1", "tls" => "require"]);
      
      if (isset($response["result"]))
      {
          $result = $response["result"];
          $codes = [];
          if ($result["state"] == "EOD")
              $codes = ["reply_codes" => ["code" => $result["code"], "enhanced" => $result["enhanced"]]];
          if ($result["code"] >= 200 and $result["code"] <= 299)
              Accept($result["reason"], $codes);
          if ($result["code"] >= 500 and $result["code"] <= 599)
              Reject($result["reason"], $codes);
          Defer($result["reason"], $codes);
      }
      else
      {
          $error = $response["error"];
          if (!$error["temporary"])
              Reject($error["message"]);
          Defer($error["message"]);
      }

    The following server settings are available in the server array.

      * **host** (string) IP-address or hostname. The default is to use lookup-mx for the recipient domain.
      * **port** (number) TCP port. The default is ``25``.
      * **helo** (string) The default is to use the system hostname.
      * **sourceip** (string) Explicitly bind an IP address. The default is to be chosen by the system.
      * **sourceipid** (string) Explicitly bind an IP address ID. The default is to be chosen by the system.
      * **nonlocal_source** (boolean) Allow binding of non-local addresses (BINDANY). The default is ``false``.
      * **saslusername** (string) If specified issue a AUTH LOGIN before MAIL FROM.
      * **saslpassword** (string) If specified issue a AUTH LOGIN before MAIL FROM.
      * **tls** (string) Use any of the following TLS modes; ``disabled``, ``optional``, ``optional_verify``, ``dane``, ``dane_require``, ``require`` or ``require_verify``. The default is ``disabled``.
      * **tls_sni** (string or boolean) Request a certificate using the SNI extension. If ``true`` the connected hostname will be used. The default is not to use SNI (``false``).
      * **tls_protocols** (string) Use one or many of the following TLS protocols; ``SSLv2``, ``SSLv3``, ``TLSv1``, ``TLSv1.1``, ``TLSv1.2`` or ``TLSv1.3``. Protocols may be separated by ``,`` and excluded by ``!``. The default is ``!SSLv2,!SSLv3``.
      * **tls_ciphers** (string) List of ciphers to support. The default is decided by OpenSSL for each ``tls_protocol``.
      * **tls_verify_host** (boolean) Verify certificate hostname (CN). The default is ``false``.
      * **tls_verify_name** (array) Hostnames to verify against the certificate's CN and SAN (NO_PARTIAL_WILDCARDS | SINGLE_LABEL_SUBDOMAINS).
      * **tls_default_ca** (boolean) Load additional TLS certificates (ca_root_nss). The default is ``false``.
      * **tls_client_cert** (string) Use the following ``pki:X`` as client certificate. The default is to not send a client certificate.
      * **xclient** (array) Associative array of XCLIENT attributes to send.
      * **protocol** (string) The protocol to use; ``smtp`` or ``lmtp``. The default is ``smtp``.
      * **mx_include** (array) Filter the MX lookup result, only including ones matching the hostnames/wildcards (NO_PARTIAL_WILDCARDS | SINGLE_LABEL_SUBDOMAINS).
      * **mx_exclude** (array) Filter the MX lookup result, removing ones matching the hostnames/wildcards (NO_PARTIAL_WILDCARDS | SINGLE_LABEL_SUBDOMAINS).

    If the send function resulted in a SMTP response you will get the SMTP response in a ``result`` field. This ``result`` field contains a ``state`` field (string) which indicates at what SMTP stage the error happened, a ``reason`` field (array of strings) containing the SMTP reponse (from the server) and a ``code`` field (number) containg the SMTP status code, optionally a ``enhanced`` (array of three numbers) field containg the SMTP enhanced status code. If a generic error happens the function will return a ``error`` field. This ``error`` field contains a ``temporary`` (boolean) field to indicate if the error may be transient and a ``reason`` field (string) containing a the error which happened.

    If a SMTP connection could be established a ``connection`` field will be included. This field contains the ``localip`` field (string), the ``remoteip`` field (string) and the ``remotemx`` field (string).

    A ``tls`` field will always be included, to indicate if the connection had TLS enabled. 

    The follwing ``state`` are available.

    +-----------------+-------------------------------------------------+
    | CONNECT         | The initial SMTP greeting                       |
    +-----------------+-------------------------------------------------+
    | HELO            |                                                 |
    +-----------------+-------------------------------------------------+
    | EHLO            |                                                 |
    +-----------------+-------------------------------------------------+
    | LHLO            |                                                 |
    +-----------------+-------------------------------------------------+
    | STARTTLS        |                                                 |
    +-----------------+-------------------------------------------------+
    | AUTH-CRAM-MD5   | In reply to sending AUTH CRAM-MD5 command       |
    +-----------------+-------------------------------------------------+
    | AUTH-PLAIN      | In reply to sending AUTH PLAIN command          |
    +-----------------+-------------------------------------------------+
    | AUTH-LOGIN      | In reply to sending AUTH LOGIN command          |
    +-----------------+-------------------------------------------------+
    | AUTH-LOGIN-USER | In reply to sending AUTH LOGIN username         |
    +-----------------+-------------------------------------------------+
    | AUTH            | In reply to last command of AUTH login attempt  |
    +-----------------+-------------------------------------------------+
    | XCLIENT         | In reply to sending a XCLIENT command           |
    +-----------------+-------------------------------------------------+
    | MAIL            |                                                 |
    +-----------------+-------------------------------------------------+
    | RCPT            |                                                 |
    +-----------------+-------------------------------------------------+
    | DATA            | In reply to sending the DATA command            |
    +-----------------+-------------------------------------------------+
    | EOD             | In reply sending the End-of-DATA                |
    +-----------------+-------------------------------------------------+
    | RSET            |                                                 |
    +-----------------+-------------------------------------------------+
    | NOOP            |                                                 |
    +-----------------+-------------------------------------------------+
    | QUIT            |                                                 |
    +-----------------+-------------------------------------------------+

.. include:: func_eod.rst

On script error
---------------

On script error :func:`Defer` is called.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Defer` is called.
