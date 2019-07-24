.. module:: predelivery

Pre-delivery
============

The pre-delivery script is executed just before a delivery attempt of a message that is being picked up from the queue.

Variables
---------

These are the pre-defined variables available.

========================== ======= ========= ===========
Variable                   Type    Read-only Description
========================== ======= ========= ===========
:ref:`$arguments <v_a1>`   array   yes       Context/hook arguments
:ref:`$message <v_m1>`     array   yes       The queued message
$context                   any     no        Delivery attempt-bound variable. It is only passed between pre and post-delivery.
========================== ======= ========= ===========

.. _v_a1:

Arguments
+++++++++

================= ======= ========================== ===========
Array item        Type    Example                    Description
================= ======= ========================== ===========
retry             number  3                          The current retry
================= ======= ========================== ===========

.. _v_m1:

Message
+++++++

============================ ======= ========================== ===========
Array item                   Type    Example                    Description
============================ ======= ========================== ===========
id                           string  "18c190a3-93f-47d7-bd..."  ID of the transaction
serverid                     string  "inbound"                  ID of the server
sender                       string  "test\@example.org"        Sender address (envelope), lowercase
:ref:`senderaddress <a1>`    array   ["localpart" => "test"...] Sender address (envelope)
recipient                    string  "test\@example.org"        Recipient address (envelope), lowercase
:ref:`recipientaddress <a1>` array   ["localpart" => "test"...] Recipient address (envelope)
transportid                  string  "inbound"                  ID of the transport profile to be used
queueid                      number  12345                      Queue ID of the message
============================ ======= ========================== ===========

.. _a1:

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

.. function:: Try()

  Try to deliver the message now. This is the default action.

  :return: doesn't return, script is terminated

.. function:: Bounce()

  Delete the message from the queue, and generate a DSN (bounce) to the sender.

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

   * **reason** (string) Optional message to be logged with the message.
   * **increment_retry** (boolean) If the retry count should be increased. The default is ``true``.
   * **reset_retry** (boolean) If the retry count should be reset to zero. The default is ``false``.
   * **transportid** (string) Set the transport ID. The default is ``$transportid``.

.. function:: CurrentConnections(namespace, entry, max)

  Can be used to limit concurrency. It returns false of the current number of connections with the same `entry` name in that `namespace` exceeds `max`, and true otherwise. The function will also occupy one "slot" after being executed, over the duration of its delivery attempt.

  :param string namespace: the namespace
  :param string entry: the entry
  :param number max: the maximum concurrency
  :rtype: boolean

  .. code-block:: hsl

    if (CurrentConnections("to-domain", $recipientdomain, 3) == false) {
            Reschedule(rand(1, 30), [
                    "reason" => "Too many concurrent connections for this domain",
                    "increment_retry" => false
            ]);
    }

.. function:: SetDestination(host, [port])

  Set the host and port for the current delivery attempt. It is not remembered for the next retry.

  :param string host: hostname or IP address
  :param number port: the TCP destination port
  :rtype: none
  :updates: ``$destination`` and ``$destinationport``

.. function:: SetProtocol(protocol)

  Set the protocol for the current delivery attempt. It is not remembered for the next retry.

  :param string protocol: ``smtp`` or ``lmtp``
  :rtype: none

.. function:: SetTLS(options)

  Set the TLS options for the current delivery attempt. It is not remembered for the next retry.

  :param array options: options array
  :rtype: none

  The following options are available in the options array.

   * **tls** (string) Use any of the following TLS modes; ``disabled``, ``optional``, ``optional_verify``, ``dane``, ``dane_require``, ``require`` or ``require_verify``.
   * **tls_sni** (string or boolean) Request a certificate using the SNI extension. If ``true`` the connected hostname will be used. The default is not to use SNI (``false``).
   * **tls_protocols** (string) Use one or many of the following TLS protocols; ``SSLv2``, ``SSLv3``, ``TLSv1``, ``TLSv1.1``, ``TLSv1.2`` or ``TLSv1.3``. Protocols may be separated by ``,`` and excluded by ``!``. The default is ``!SSLv2,!SSLv3``.
   * **tls_ciphers** (string) List of ciphers to support. The default is decided by OpenSSL for each SSL/TLS protocol.
   * **tls_verify_host** (boolean) Verify certificate hostname (CN). The default is ``false``.
   * **tls_verify_name** (array) Hostnames to verify against the certificate's CN and SAN (NO_PARTIAL_WILDCARDS | SINGLE_LABEL_SUBDOMAINS).
   * **tls_default_ca** (boolean) Load additional TLS certificates (ca_root_nss). The default is ``false``.
   * **tls_client_cert** (string) Use the following ``pki:X`` as client certificate. The default is to not send a client certificate.
   * **tls_capture_peer_cert** (boolean) If set to true, the peer certificate will be available in the :func:`postdelivery.GetTLS` results. The default is ``false``.

.. function:: SetSASL(username, password)

  Set the SASL `AUTH` username and password for the current delivery attempt. It is not remembered for the next retry.

  :param string username: username
  :param string password: password
  :rtype: none

.. function:: SetXCLIENT(attributes)

  Send the following XCLIENT xclient attributes. It is not remembered for the next retry.

  :param array attributes: associative array of XCLIENT attributes to send
  :rtype: none

.. function:: SetHELO(hostname)

  Set the `HELO` hostname for the current delivery attempt. It is not remembered for the next retry.

  :param string hostname: a hostname
  :rtype: none

.. function:: SetSourceIP(id, [options])

  This function changes the source IP of the current delivery attempt. It is not remembered for the next retry.

  :param id: the IP address ID to use
  :type id: string or array
  :param array options: options array
  :rtype: none
  :updates: ``$sourceip`` to the actual IP address of ``id``

  The following options are available in the options array.

   * **nonlocal_source** (boolean) If the system setting 'system_nonlocal_source' is enabled, `id` may be an IP. The default is ``false``.

  .. note::
  	If `id` is given as an array, only one item for each IP family may be given.

.. function:: SetSender(sender)

  Set the sender `MAIL FROM` for the current delivery attempt. It is not remembered for the next retry.

  :param sender: an email address, either as a string or a tuple with localpart and domain
  :type sender: string or array
  :rtype: none
  :updates: ``$sender``, ``$senderlocalpart`` and ``$senderdomain``

.. function:: SetSenderParams(params)

  Set the sender `MAIL FROM` params for the current delivery attempt. It is not remembered for the next retry.

  :param array params: key-value array of params
  :rtype: none

.. function:: SetRecipient(recipient)

  Set the recipient `RCPT TO` for the current delivery attempt. It is not remembered for the next retry.

  :param recipient: an email address, either as a string or a tuple with localpart and domain
  :type recipient: string or array
  :rtype: none
  :updates: ``$recipient``, ``$recipientlocalpart`` and ``$recipientdomain``

.. function:: SetRecipientParams(params)

  Set the recipient `RCPT TO` params for the current delivery attempt. It is not remembered for the next retry.

  :param array params: key-value array of params
  :rtype: none

.. function:: SetDSN(options)

  Set the DSN options for the current delivery attempt if a DSN were to be created. It is not remembered for the next retry.

  :param array options: options array
  :rtype: none

  The following options are available in the options array.

   * **transportid** (string) Set the transport ID. The default is either choosen by the transport or automatically assigned.
   * **recipient** (string) Set the recipient. The default is ``$recipientlocalpart`` at ``$recipientdomain``.
   * **metadata** (array) Add additional metadata (KVP) to the DSN.
   * **from** (string) Set the From-header address of the DSN.
   * **from_name** (string) Set the From-header display name of the DSN.
   * **dkim** (array) Set the DKIM options of the DSN (``selector``, ``domain``, ``key`` including the options available in :func:`MIME.signDKIM`).

.. function:: SetMetaData(metadata)

  This function updates the queued message's metadata in the database. It is consequentially remembered for the next retry.
  The metadata must be an array with both string keys and values.

  :param array metadata: metadata to set
  :rtype: none

  .. note::

    To work-around the data type limitation of the metadata; data can be encoded using :func:`json_encode`.

.. function:: GetMetaData()

  Get the metadata set by :func:`SetMetaData`. If no data was set, an empty array is returned.

  :return: the data set by :func:`SetMetaData`
  :rtype: array

.. function:: GetMailQueueMetric([options])

  Return metric information about the mail queue, it can be used to enforce quotas.

  :param array options: options array
  :rtype: number

.. include:: func_getmailqueuemetric.rst

.. function:: GetMailFile([options])

  Return a :class:`File` class to the current mail file.

  :param array options: an options array
  :return: A File class to the current mail file.
  :rtype: File

  The following options are available in the options array.

   * **changes** (boolean) Include changes done to the original message. The default is ``false``.

On script error
---------------

On script error ``Reschedule(300)`` is called with ``increment_retry`` set to false.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Try` is called.
