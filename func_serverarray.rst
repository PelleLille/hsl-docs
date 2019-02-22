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
 * **tls_capture_peer_cert** (boolean) If set to true, the peer certificate will be available in the extended results. The default is ``false``.
 * **xclient** (array) Associative array of XCLIENT attributes to send.
 * **protocol** (string) The protocol to use; ``smtp`` or ``lmtp``. The default is ``smtp``.