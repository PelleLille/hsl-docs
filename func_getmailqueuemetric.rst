  The following options are available in the options array.

   * **metric** (string) Metric to be returned; `count` or `bytes`. The default is ``count``.
   * **filter** (array) Any of the available filters, see below. The default is no filters.

  The following filters are available in the filters array.

  =============== =======
  Type            Example
  =============== =======
  remoteip        $connection["remoteip"]
  saslusername    $connection["auth"]["username"]
  sender          $transaction["sender"]
  senderdomain    $transaction["senderaddress"]["domain"]
  recipient       $transaction["recipients"][0]["recipient"]
  recipientdomain $transaction["recipients"][0]["address"]["domain"]
  transportid     $transaction["recipients"][0]["transportid"]
  retry           1
  metadata.x      any metadata
  =============== =======

  .. code-block:: hsl

	$queuesize = GetMailQueueMetric(
		[
			"metric" => "bytes",
			"filter" => [
				"senderdomain" => ["example.com" , "example.net"],
				"transportid" => "outbound"
			]
		]
	) / 1024 / 1024;
	if ($queuesize > 500) {
		Defer("Current queue for outbound exceeds 500 MiB");
	}

  .. note::
	  If multiple filters of the same type are given using array notation, any of them may match.
