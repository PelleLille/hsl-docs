.. module:: predelivery

Pre-delivery
============

The pre-delivery script is executed just before a delivery attempt of a message that is being picked up from the queue.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available. Some of them can be changed using the functions below.

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$receivedtime     number  1445937340                 The unix time (in UTC) when the message was received
$sourceip         string  "10.0.0.1"                 The delivery source IP (initially defined by the transport profile)
$destination      string  "172.16.1.25"              The destination host (initially defined by the transport profile)
$destinationport  number  25                         The destination port (initially defined by the transport profile)
$senderip         string  "192.168.1.11"             IP address of the sender
$senderhelo       string  "mail.example.com"         HELO message of sender
$saslusername     string  "mailuser"                 SASL username
$sender           string  "test\@example.org"        E-mail address of sender (envelope)
$senderdomain     string  "example.org"              Domain part of sender's address (envelope)
$recipient        string  "test\@example.com"        E-mail address of recipient (envelope)
$recipientdomain  string  "example.com"              Domain part of recipient's address (envelope)
$retry            number  3                          The current retry count
$retries          number  30                         The maximum number of retries for that message
$messageid        string  "18c190a3-93f-47d7-bd..."  ID of the message
$actionid         number  1                          Same as $actionid in DATA context
$queueid          number  12345                      Queue ID of the message
$serverid         string  "mailserver\:1"            ID of the mailserver profile
$transportid      string  "mailtransport\:1"         ID of the transport profile that is used
================= ======= ========================== ===========

These are the writable pre-defined variables available.

================= ======= ===========
Variable          Type    Description
================= ======= ===========
$context          any     Delivery attempt-bound variable. It is only passed between pre and post-delivery.
================= ======= ===========

Functions
---------

.. function:: Try()

  Try to deliver the message now. This is the default action.

  :return: doesn't return, script is terminated

.. function:: Bounce()

  Delete the message from the queue, and generating a DSN (bounce) to the sender.

  :return: doesn't return, script is terminated

.. function:: Delete()

  Delete the message from the queue, without generating a DSN (bounce) to the sender.

  :return: doesn't return, script is terminated

.. function:: Reschedule(delay, [options])

  Reschedule the message for `delay` seconds.

  :param number delay: delay in seconds
  :param array options: options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **reason** (string) optional message to be logged with the message.
   * **increment_retry** (boolean) if the retry count should be increased. The default is ``true``.
   * **reset_retry** (boolean) if the retry count should be reset to zero. The default is ``false``.
   * **transportid** (string) set the transportid. The default is ``$transportid``

.. function:: CurrentConnections(namespace, entry, max)

  Can be used to limit concurrency. It returns false of the current number of connections with the same `entry` name in that `namespace` exceeds `max`, and true otherwise. The function will also occupy one "slot" after being executed, over the duration of its delivery attempt.

  :param string namespace: the namespace
  :param string entry: the entry
  :param number max: the maximum concurrency

  .. code-block:: hsl

    if (CurrentConnections("to-domain", $recipientdomain, 3) == false) {
            Reschedule(rand(1, 30), [
                    "reason" => "Too many concurrent connections for this domain",
                    "increment_retry" => false
            ]);
    }

.. function:: SetDestination(host, [port])

  Set the host and port for the current delivery attempt (it is not remembered for the next retry).

  :param string host: a hostname or IP-address
  :param number port: the TCP port to use
  :rtype: none
  :updates: ``$destination`` and ``$destinationport``

.. function:: SetProtocol(protocol)

  Set the protocol for the current delivery attempt (it is not remembered for the next retry).

  :param string protocol: ``smtp`` or ``lmtp``
  :rtype: none

.. function:: SetTLS(options)

  Set the TLS options for the current delivery attempt (it is not remembered for the next retry).

  :param array options: options array
  :rtype: none

  The following options are available in the options array.

   * **tls** (string) Use any of the following TLS modes; ``disabled``, ``optional``, ``optional_verify``, ``dane``, ``dane_require``, ``require`` or ``require_verify``. The default is ``disabled``.
   * **tls_protocols** (string) Use one or many of the following TLS protocols; ``SSLv2``, ``SSLv3``, ``TLSv1``, ``TLSv1.1`` or ``TLSv1.2``. Protocols may be separated by ``,`` and excluded by ``!``. The default is ``!SSLv2,!SSLv3``.
   * **tls_ciphers** (string) List of ciphers to support. The default is decided by OpenSSL for each SSL/TLS protocol.
   * **tls_verify_host** (boolean) Verify certificate hostname (CN). The default is ``false``.
   * **tls_verify_name** (array) Hostnames to verify against the certificate's CN and SAN.
   * **tls_default_ca** (boolean) Load additional TLS certificates (ca_root_nss). The default is ``false``.
   * **tls_client_cert** (string) Use the following ``pki:X`` as client certificate. The default is to not send a client certificate.
   * **tls_capture_peer_cert** (boolean) If set to true, the peer certificate will be available in the :func:`postdelivery.GetTLS` results. The default is ``false``.

.. function:: SetSASL(username, password)

  Set the SASL `AUTH` username and password for the current delivery attempt (it is not remembered for the next retry).

  :param string username: username
  :param string password: password
  :rtype: none

.. function:: SetHELO(hostname)

  Set the `HELO` hostname for the current delivery attempt (it is not remembered for the next retry).

  :param string hostname: a hostname
  :rtype: none

.. function:: SetSourceIP(netaddr, [options])

  This function changes the source IP of the current delivery attempt (it is not remembered for the next retry).

  :param netaddr: the ``netaddr:X`` to use
  :type netaddr: string or array
  :param array options: options array
  :rtype: none
  :updates: ``$sourceip`` to the actual IP address of ``netaddr:X``

  The following options are available in the options array.

   * **nonlocal_source** (boolean) if the system setting 'system_nonlocal_source' is enabled, `netaddr` may be an IP. The default is ``false``.

  .. note::
  	If `netaddr` is given as an array only one ``netaddr:X`` for each IP family may be given.

.. function:: SetSender(sender)

  Set the sender `MAIL FROM` for the current delivery attempt (it is not remembered for the next retry).

  :param string sender: an e-mail address
  :rtype: none
  :updates: ``$sender`` and ``$senderdomain``

.. function:: SetSenderParams(params)

  Set the sender `MAIL FROM` params for the current delivery attempt (it is not remembered for the next retry).

  :param array params: key-value array of params
  :rtype: none

.. function:: SetRecipient(recipient)

  Set the recipient `RCPT TO` for the current delivery attempt (it is not remembered for the next retry).

  :param string recipient: an e-mail address
  :rtype: none
  :updates: ``$recipient`` and ``$recipientdomain``

.. function:: SetRecipientParams(params)

  Set the recipient `RCPT TO` params for the current delivery attempt (it is not remembered for the next retry).

  :param array params: key-value array of params
  :rtype: none

.. function:: SetDSN(options)

  Set the DSN options for the current delivery attempt if a DSN were to be created (it is not remembered for the next retry).

  :param array options: options array
  :rtype: none

  The following options are available in the options array.

   * **transportid** (string) Set the transportid. The default is either choosen by the transport or automatically assigned.
   * **recipient** (string) Set the recipient. The default is ``$sender``.
   * **metadata** (array) Add additional metadata to the DSN (KVP).

.. function:: SetMetaData(metadata)

  This function sets the metadata for the current message. The metadata must be an array with both string keys and values.

  :param array metadata: metadata to set
  :rtype: none

  .. note::

    To work-around the data type limitation of the metadata; data can be encoded using :func:`json_encode`.

.. function:: GetMetaData()

  Get the metadata set by :func:`SetMetaData`. If no data was set, an empty array is returned.

  :return: the data set by :func:`SetMetaData`
  :rtype: array

.. function:: GetMailQueueMetric(options)

  Return metric information about the mail queue, it can be used to enforce quotas.

  :param array options: options array

.. include:: func_getmailqueuemetric.rst

.. function:: GetMailFile()

  Return a :class:`File` class to the current mail file.

  :return: A File class to the current mail file.
  :rtype: File

  .. note::

  	The file is returned in an unmodified state as received (only with a Received header applied).

On script error
---------------

On script error ``Reschedule(300)`` is called with ``increment_retry`` set to false.

On implicit termination
-----------------------

If not explicitly terminated then ``Try()`` is called.
