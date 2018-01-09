.. function:: GetTLS([options])

  Get the TLS information for a connection.

  :param array options: options array
  :rtype: array

  The following options are available in the options array.

   * **fingerprint** (string) Generate the fingerprint of the certificate using one of the following hash function (``md5``, ``sha1``, ``sha256`` or ``sha512``). The default no hashing.

  The following items are available in the result.

   * **started** (boolean) If STARTTLS was issued.
   * **protocol** (string) The protocol used (eg. ``TLSv1.2``)
   * **ciphers** (string) The cipher used (eg. ``ECDHE-RSA-AES256-SHA384``).
   * **keysize** (number) The keysize used (eg. ``256``).
   * **peer_cert** (array) The peer certificate (if provided by the client). Same format as :func:`TLSSocket.getpeercert`.
   * **peer_cert_error** (number) The peer certificate validation error (see OpenSSLs SSL_get_verify_result(3)).
