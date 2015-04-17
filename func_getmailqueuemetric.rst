  The following options are available in the options array.

   * **metric** (string) Metric to be returned; `count` or `bytes`. The default is ``count``.
   * **filter** (array) Any of the available filters, see below. The default is no filters.

  The following filters are available in the filters array.

  =============== =======
  Type            Example
  =============== =======
  senderip        $senderip
  saslusername    $saslusername
  sender          $sender
  senderdomain    $senderdomain
  recipientdomain $recipientdomain
  transportid     $transportid
  retry           1
  =============== =======

  .. code-block:: hsl

	$queuesize = GetMailQueueMetric(
		[
			"metric" => "bytes",
			"filter" => [
				"senderdomain" => ["example.com" , "example.net"],
				"transportid" => "mailtransport:2"
			]
		]
	) / 1024 / 1024;
	if ($queuesize > 500) {
		Defer("Current queue for mailtransport:2 exceeds 500 MiB");
	}

  .. note::
	  If multiple filters of the same type are given using array notation, any of them may match.
