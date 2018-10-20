<?php

return [
    'serverKey' => env('WEBPUSH_SERVER_KEY', 'AAAA53cyf58:APA91bG6GuARsj0SEJ_vSO1xCFLlK6YFh8i6Z8_mzSBDFS6zqcL9MyVIPHFVChARIYNhoNMt3rmt20XSSmkk6DNfzWElWiKeEqQ_aBwZp2AWXZuWCmTawQg2C3j2a5Z4dAYIQindoY7e'),
    'senderId' => env('WEBPUSH_SENDER_ID', '994137243551'),
];

/**
 *curl 'https://fcm.googleapis.com/fcm/send' -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36' -H 'Referer: https://test.frserver.ru' -H 'Origin: https://test.frserver.ru' -H 'Authorization: key=AAAA53cyf58:APA91bG6GuARsj0SEJ_vSO1xCFLlK6YFh8i6Z8_mzSBDFS6zqcL9MyVIPHFVChARIYNhoNMt3rmt20XSSmkk6DNfzWElWiKeEqQ_aBwZp2AWXZuWCmTawQg2C3j2a5Z4dAYIQindoY7e' -H 'Content-Type: application/json' --data-binary $'{"data":{"title":"Привет, тебе уведомление","body":"Пора что-то сделать","icon":"https://peter-gribanov.github.io/serviceworker/Bubble-Nebula.jpg","image":"https://peter-gribanov.github.io/serviceworker/Bubble-Nebula_big.jpg","click_action":"https://google.com"},"to":"fgSxHjpMRPk:APA91bEYm0GokgKgC5ZaL3_KLKlHfjh6CLqlCMzHPo6t7UTWJHsCTXN5dGqVjsUUy71KjNgVKYHIZi16k8QqlEfSjABOy8yZr6iQFIzQsp5XqJKOZO067tL5Y4hZO86tp7ijf6zUJEUc"}' --compressed
 *
 *fgSxHjpMRPk:APA91bEYm0GokgKgC5ZaL3_KLKlHfjh6CLqlCMzHPo6t7UTWJHsCTXN5dGqVjsUUy71KjNgVKYHIZi16k8QqlEfSjABOy8yZr6iQFIzQsp5XqJKOZO067tL5Y4hZO86tp7ijf6zUJEUc
 */