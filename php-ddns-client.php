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

$user = 'test';
$pass = 'test';
$hosts[] = 'test.mine.nu';

function ddns_get_ipaddress()
{
	$content = file_get_contents('http://checkip.dyndns.com');

	$matched = $content !== FALSE && (bool)preg_match('/\d*\.\d*\.\d*\.\d*/', $content, $matches);

	if ($matched)
		return $matches[0];
	else
		return FALSE;
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

function ddns_update_host($host, $newIP)
{
	global $user, $pass;

	$url = 'http://#user#:#pass#@members.dyndns.org/nic/update?hostname=#host#&myip=#ip#';
	$url = str_replace('#user#', $user, $url);
	$url = str_replace('#pass#', $pass, $url);
	$url = str_replace('#host#', $host, $url);
	$url = str_replace('#ip#', $newIP, $url);

	$opts = array('http' => array('user_agent' => 'php-ddns-client 0.1'));
	$context = stream_context_create($opts);

	$content = file_get_contents($url, false, $context);

	$matched = $content !== FALSE && (bool)preg_match('/^\w*/', $content, $matches);

	if ($matched === FALSE)
		return FALSE;

	$result = strtolower($matches[0]);

	return $result == 'good';
}

