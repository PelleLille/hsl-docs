.. module:: predelivery

Pre-delivery
============

The pre-delivery script is executed just before an email is being put into the active queue
(for delivery as soon as the active queue policies allow).

Variables
---------

These are the pre-defined variables available.

========================== ======= ========= ===========
Variable                   Type    Read-only Description
========================== ======= ========= ===========
:ref:`$arguments <v_p1>`   array   yes       Context/hook arguments
:ref:`$message <v_m2>`     array   yes       The queued message
$context                   any     no        Delivery attempt-bound variable. It is only passed between pre and post-delivery.
========================== ======= ========= ===========

.. _v_p1:

Arguments
+++++++++

================= ======= ========================== ===========
Array item        Type    Example                    Description
================= ======= ========================== ===========
retry             number  3                          The current retry
================= ======= ========================== ===========

.. _v_m2:

Message
+++++++

============================ ======= ========================== ===========
Array item                   Type    Example                    Description
============================ ======= ========================== ===========
:ref:`id <id1>`              array   ["transaction" => "18..."  ID of the message
ts                           number  1575558785.1234            Unix time of transaction
serverid                     string  "inbound"                  ID of the server
sender                       string  "test\@example.org"        Sender address (envelope), lowercase
:ref:`senderaddress <a1>`    array   ["localpart" => "test"...] Sender address (envelope)
recipient                    string  "test\@example.org"        Recipient address (envelope), lowercase
:ref:`recipientaddress <a1>` array   ["localpart" => "test"...] Recipient address (envelope)
transportid                  string  "inbound"                  ID of the transport profile to be used
jobid                        string  "customidentifier1"        Job ID of the message
size                         number  412311                     Message size in bytes
============================ ======= ========================== ===========

.. _id1:

id
>>>>>>>

============================ ======= ========================== ===========
Array item                   Type    Example                    Description
============================ ======= ========================== ===========
transaction                  string  "18c190a3-93f-47d7-bd..."  ID of the transaction
queue                        number  1                          Queue ID of the message
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

.. function:: Try([options])

  Accept the email into the active queue
  (for delivery as soon as the active queue policies allow). This is the default action.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array. Some are used to override
  the properties otherwise chosen based on the email's metadata and transport settings.

    * **host** (string) The IP address or hostname to connect to, or "lookup-mx" for MX lookup. Overrides the transport setting.
    * **port** (number) TCP port to connect to. Overrides the transport setting.
    * **sender** (string or array) Change the sender email address, either as a string or an associative array with a ``localpart``, ``domain`` and ``params``. Overrides the queued email's metadata.
    * **recipient** (string or array) Change the sender email address, either as a string or an associative array with a ``localpart``, ``domain`` and ``params``. Overrides the queued email's metadata.
    * **helo** (string) The SMTP HELO/EHLO hostname. It can also be specified per source IP. Overrides the transport setting.
    * **sourceip** (array) Source (local) IP(s) to use. The array may contain either strings (of ID's) or associative arrays with ``id`` or ``address`` (literal) and ``helo``. Overrides the transport setting.
    * **nonlocal_source** (boolean) Allow binding of non-local addresses (BINDANY). The default is ``false``.
    * **saslusername** (string) If specified issue a AUTH LOGIN before MAIL FROM. Overrides the transport setting.
    * **saslpassword** (string) If specified issue a AUTH LOGIN before MAIL FROM. Overrides the transport setting.
    * **tls** (string) Use any of the following TLS modes; ``disabled``, ``optional``, ``optional_verify``, ``dane``, ``dane_require``, ``require`` or ``require_verify``. Overrides the transport setting.
    * **tls_sni** (string or boolean) Request a certificate using the SNI extension. If ``true`` the connected hostname will be used. The default is not to use SNI (``false``).
    * **tls_protocols** (string) Use one or many of the following TLS protocols; ``SSLv2``, ``SSLv3``, ``TLSv1``, ``TLSv1.1``, ``TLSv1.2`` or ``TLSv1.3``. Protocols may be separated by ``,`` and excluded by ``!``. The default is ``!SSLv2,!SSLv3``.
    * **tls_ciphers** (string) List of ciphers to support. The default is decided by OpenSSL for each ``tls_protocol``.
    * **tls_verify_host** (boolean) Verify certificate hostname (CN). The default is ``false``.
    * **tls_verify_name** (array) Hostnames to verify against the certificate's CN and SAN (NO_PARTIAL_WILDCARDS | SINGLE_LABEL_SUBDOMAINS).
    * **tls_default_ca** (boolean) Load additional TLS certificates (ca_root_nss). The default is ``false``.
    * **tls_client_cert** (string) Use the following ``pki:X`` as client certificate. The default is to not send a client certificate.
    * **xclient** (array) Associative array of XCLIENT attributes to send.
    * **protocol** (string) The protocol to use; ``smtp`` or ``lmtp``. Overrides the transport setting.
    * **mx_include** (array) Filter the MX lookup result, only including ones matching the hostnames/wildcards (NO_PARTIAL_WILDCARDS | SINGLE_LABEL_SUBDOMAINS).
    * **mx_exclude** (array) Filter the MX lookup result, removing ones matching the hostnames/wildcards (NO_PARTIAL_WILDCARDS | SINGLE_LABEL_SUBDOMAINS).
    * **jobid** (string) Job ID of the message.
    * **timeout** (array) Associative array of :ref:`state <as1>` and the timeout in seconds. The default is set according to RFC2821.
    * **connect_timeout** (number) The connect timeout in seconds. The default is ``30`` seconds.

.. function:: Queue([options])

  Queue the message to be retried later. If the maximum retry count is exceeded; the message is either bounced or deleted depending on the transport's settings.

  :param array options: options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **hold** (boolean) Put the message in the hold (inactive) queue. The default is ``false``.
   * **delay** (number) the delay in seconds. The default is according to the current transports retry delay.
   * **reason** (string) Optional message to be logged with the message.
   * **increment_retry** (boolean) If the retry count should be increased. The default is ``true``.
   * **reset_retry** (boolean) If the retry count should be reset to zero. The default is ``false``.
   * **transportid** (string) Set the transport ID.  The default the current `transportid`.
   * **quotas** (array) An array of quotas to be associated with the message.

.. function:: Bounce()

  Delete the message from the queue, and generate a DSN (bounce) to the sender.

  :return: doesn't return, script is terminated

.. function:: Delete()

  Delete the message from the queue, without generating a DSN (bounce) to the sender.

  :return: doesn't return, script is terminated

.. function:: SetDSN(options)

  Set the DSN options for the current delivery attempt if a DSN were to be created. It is not remembered for the next retry.

  :param array options: options array
  :rtype: none

  The following options are available in the options array.

   * **transportid** (string) Set the transport ID. The default is either choosen by the transport or automatically assigned.
   * **recipient** (string or array) Set the recipient of the DSN, either as a string or an associative array with a ``localpart`` and ``domain``.
   * **metadata** (array) Add additional metadata (KVP) to the DSN.
   * **from** (string or array) Set the From-header address of the DSN, either as a string or an associative array with a ``localpart`` and ``domain``.
   * **from_name** (string) Set the From-header display name of the DSN.
   * **dkim** (array) Set the DKIM options of the DSN (``selector``, ``domain``, ``key`` including the options available in :func:`MIME.signDKIM`).
   * **jobid** (string) Job ID of the message.

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

On script error
---------------

On script error ``Queue(["delay" => 300, "increment_retry" => false])`` is called.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Try` is called.
