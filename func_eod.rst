DKIM
++++

These are DKIM-related functions, including DMARC. Other modules, such as ARC, is available in the authentication folder of the `script library <https://docs.halon.io/go/scriptlibrary>`_.

.. function:: ScanDMARC([options])

  Returns the `DMARC <https://docs.halon.io/go/dmarc>`_ policy to apply to the message for the From-address. It will return an associative array containing the domain as result. If the domain cannot be properly extracted or missing an error message will be returned.

  :param array options: options array
  :return: associative array containing the domain and result or an error.
  :rtype: array or string

  The following options are available in the options array.

   * **ip** (string) Override the client `ip` for SPF lookup.
   * **helo** (string) Override the `helo` host for SPF lookup.
   * **domain** (string) Override the mail from `domain` for SPF lookup.

  ================================== ==========
  "permerror"                        An unknown error occurred (more details may be available in the log)
  ["example.com" => "temperror"]     A temporary error occurred (but the domain was known)
  ["example.com" => "policy_absent"] No DMARC policy for domain
  ["example.com" => "pass"]          The DMARC passed
  ["example.com" => "none"]          The policy resulted in none
  ["example.com" => "reject"]        The policy resulted in reject
  ["example.com" => "quarantine"]    The policy resulted in quarantine
  ================================== ==========

Embedded content scanning
+++++++++++++++++++++++++

These functions scan the message file using various engines.
While the DLP engine ``dlpd`` is included in all software packages, the embedded anti-spam and anti-virus engines are only available in the full system distribution (virtual machine) package.
All connectors are available in the `script library <https://docs.halon.io/go/scriptlibrary>`_.

.. function:: ScanDLP([patterns, [options]])

  Scan a message using the builtin `DLP <https://docs.halon.io/go/dlp>`_ engine.

  :param array patterns: array of pre-configured rules or an array of custom rules
  :param array options: options array
  :return: all patterns found (may include `ERR_` rules even if not explicitly given in the `patterns` argument)
  :rtype: array

  The following options are available in the options array.

   * **stop_on_match** (boolean) processing the mail when one match (of the requested type) is found. The default is ``false``.
   * **timeout** (number) set an approximate timeout time in seconds. The default in no timeout.
   * **recursion_limit** (number) how deep to dig through MIME trees, archive files (such as ZIP), etc. The default is ``9``.
   * **partid** (boolean) return a data structure with the partid where the pattern is found. The default is ``false``.
   * **extended_result** (boolean) Return extended results. The default is ``false``.

  The following results are available in the extended results array.

	   * **rules** (array) The rules matched

	   On error the following items are available.

	   * **error** (boolean) Indicates if there was an error during the scanning

  The patterns array may either be an array of pre-configured rules by name.

  .. code-block:: hsl

	["RULE1", "RULE2", ...]

  Or a custom rule with the patterns provided. A custom rule may contain multiple types (eg. `filename`, `sha1hash` etc.) with multiple patterns each. The available types may be extracted from the DLP configuration.

  .. code-block:: hsl

	["RULE1" => ["filename" => ["/\\.exe$/i", "/\\.zip$/i"], "sha1hash" => ["..."]], ...]

  .. warning::

	Do not allow untrusted users to add custom regular expression, since not all regular expressions are safe. All user data should be escaped using :func:`pcre_quote` before compiled into a regular expression.

  There are some builtin rules which may occur.

  ========================== ===========
  Builtin rules              Description
  ========================== ===========
  ERR_UNKNOWN_ERROR          An unknown error occurred (more details may be available in the log)
  ERR_PASSWORD_PROTECTED     The archive is password protected
  ERR_RECURSION_LIMIT        The archive is too nested
  ========================== ===========

.. function:: ScanRPD([options])

  Scan the message using `Cyren <https://docs.halon.io/go/cyren>`_; anti-spam ``ctasd`` (RPD and LocalView) and zero-hour malware detection (VOD). It runs in either inbound or outbound mode, and it's important to configure this correctly with the `outbound` option.

  :param array options: options array
  :return: score or refid
  :rtype: number, string or array

  The following options are available in the options array.

   * **refid** (boolean) Return RefID (used to report FN and FP). The default is ``false``.
   * **outbound** (boolean) Use RPD in outbound mode. The default is ``false``.
   * **extended_result** (boolean) Return extended results. The default is ``false``.
   * **senderip** (string) Change the value of the `X-CTCH-SenderIP` header.
   * **mailfrom** (string) Change the value of the `X-CTCH-MailFrom` header.
   * **senderid** (string) Set the value of the `X-CTCH-SenderID` header (only for outbound).
   * **rcptcount** (number) Set the value of the `X-CTCH-RcptCount` header (only for outbound).

  The following results are available in the extended results array.

	   * **refid** (string) The refid
	   * **rules** (array) The LocalView spam rules matched
	   * **spam_score** (number) The spam score
	   * **spam_class** (string) The spam class
	   * **virus_score** (number) The virus score
	   * **virus_class** (string) The virus class

	   On error the following items are available.

	   * **error** (boolean) Indicates if there was an error during the scanning

  RPD’s anti-spam classification scores and class names

  ===== ================= ===========
  Score Class             Description
  ===== ================= ===========
  0     non-spam, unknown Unknown
  10    suspect           Suspect
  40    valid-bulk        Valid bulk
  50    bulk              Bulk
  100   spam              Spam
  ===== ================= ===========

  RPD’s anti-virus classification scores and class names

  ===== ================== ===========
  Score Class              Description
  ===== ================== ===========
  0     non-virus, unknown Unknown
  50    medium             Medium probability
  100   virus, high        High probability
  ===== ================== ===========

.. function:: ScanSA([options])

  Scan the message using `SpamAssassin <https://docs.halon.io/go/distspamassassin>`_.

  :param array options: options array
  :return: score or rules
  :rtype: number or array

  The following options are available in the options array.

   * **rules** (boolean) Return rules in an associative array with scores. The default is ``false``.
   * **extended_result** (boolean) Return extended results. The default is ``false``.

  The following results are available in the extended results array.

	   * **rules** (array) The rules matched

	   On error the following items are available.

	   * **error** (boolean) Indicates if there was an error during the scanning

  ========================== ===== ===========
  Builtin rules              Score Description
  ========================== ===== ===========
  NOT_SCANNED_TOO_BIG        0     Message was to big too big to be scanned
  NOT_SCANNED_QUEUE_TOO_LONG 0     Queue was too long to SpamAssassin
  ========================== ===== ===========

  A score of `5` or higher is what most people accept to be considered spam.

.. function:: ScanKAV([options])

  Scan the message using the Sophos anti-virus.

  :param array options: options array
  :return: any viruses found
  :rtype: array

  The following options are available in the options array.

   * **extended_result** (boolean) Return extended results. The default is ``false``.

  The following results are available in the extended results array.

	   * **rules** (array) The rules matched

	   On error the following items are available.

	   * **error** (boolean) Indicates if there was an error during the scanning

.. function:: ScanCLAM([options])

  Scan the message using ClamAV anti-virus.

  :param array options: options array
  :return: any viruses found
  :rtype: array

  The following options are available in the options array.

   * **extended_result** (boolean) Return extended results. The default is ``false``.
   * **signature_exclude** (array) List of signatures to ignore / whitelist.

  The following results are available in the extended results array.

	   * **rules** (array) The rules matched

	   On error the following items are available.

	   * **error** (boolean) Indicates if there was an error during the scanning