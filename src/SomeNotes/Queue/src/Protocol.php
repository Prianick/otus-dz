<?php

namespace OtusDZ\Src\SomeNotes\Queue\src;

class Protocol
{
    /**
     * @param string $request
     * @return array
     */
    public function getHeadersAndBody(string $request): array
    {
        $request = str_replace(QueueManager::END_MSG_POINT, '', $request);
        $request = explode("\n\n", $request);

        $headerLines = explode("\n", $request[0]);
        $headers = [];
        foreach ($headerLines as $header) {
            $tmp = explode(':', $header);
            if (empty($tmp[0])) {
                break;
            }
            $headers[trim($tmp[0])] = trim($tmp[1]);
        }

        $body = '';
        if (!empty($request[1])) {
            $body = $request[1];
        }

        return [$headers, $body];
    }
}
