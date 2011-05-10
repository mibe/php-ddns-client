<?php

/*
 * This is a simple client for updating hosts at a Dynamic DNS service
 * provider.
 */


/*
 * Copyright (C) 2011 Michael Bemmerl
 *
 * Permission to use, copy, modify, and/or distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY
 * SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION
 * OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN
 * CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */

function ddns_get_ipaddress()
{
	$content = file_get_contents('http://checkip.dyndns.com');
	$content = strip_tags($content);
	$content = substr($content, strpos($content, ': ') + 2);
	return $content;
}

function ddns_resolve_host($host)
{
	$ips = dns_get_record($host, DNS_A);

	if ($ips === FALSE)
		return FALSE;

	$result = array();

	foreach($ips as $ip)
		$result[] = $ip['ip'];

	return $result;
}
