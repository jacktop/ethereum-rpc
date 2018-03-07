<?php
/**
 * This file is a part of "furqansiddiqui/ethereum-rpc" package.
 * https://github.com/furqansiddiqui/ethereum-rpc
 *
 * Copyright (c) 2018 Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit following link:
 * https://github.com/furqansiddiqui/ethereum-rpc/blob/master/LICENSE
 */

declare(strict_types=1);

namespace EthereumRPC;

use EthereumRPC\Exception\GethException;
use HttpClient\Response\JSONResponse;

/**
 * Class Personal
 * @package EthereumRPC
 */
class Personal
{
    /** @var EthereumRPC */
    private $client;

    /**
     * Personal constructor.
     * @param EthereumRPC $ethereum
     */
    public function __construct(EthereumRPC $ethereum)
    {
        $this->client = $ethereum;
    }

    /**
     * @param string $command
     * @param array|null $params
     * @return JSONResponse
     * @throws Exception\ConnectionException
     * @throws GethException
     * @throws \HttpClient\Exception\HttpClientException
     */
    private function accountsRPC(string $command, ?array $params = null): JSONResponse
    {
        return $this->client->jsonRPC($command, null, $params);
    }

    /**
     * @param string $password
     * @return string
     * @throws Exception\ConnectionException
     * @throws GethException
     * @throws \HttpClient\Exception\HttpClientException
     */
    public function newAccount(string $password): string
    {
        $request = $this->accountsRPC("personal_newAccount", [$password]);
        $account = $request->get("result");
        if (!is_string($account)) {
            throw GethException::unexpectedResultType("personal_newAccount", "string", gettype($account));
        } elseif (!preg_match('/^(0x)?[a-f0-9]{40,42}$/', $account)) {
            throw new GethException('Invalid newly created ETH address');
        }

        return $account;
    }
}