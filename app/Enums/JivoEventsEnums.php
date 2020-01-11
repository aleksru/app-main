<?php


namespace App\Enums;


class JivoEventsEnums
{
    /**
    chat_accepted - Оператор принял запрос диалога от клиента (нажал в программе кнопку Ответить)
    client_updated - Обновились контактные данные о клиенте (либо клиент представился, либо оператор сам внёс контакты в программе)
    chat_assigned - Диалог был прикреплен к карточке в CRM.
    chat_finished - Диалог завершился (был закрыт в программе либо нажатием на крестик рядом с именем клиента, либо автоматически после того, как клиент покинул сайт)
    offline_message - Было отправлено оффлайн-сообщение, когда не было операторов онлайн
     */
    const CHAT_ACCEPTED = 'chat_accepted';
    const CLIENT_UPDATED = 'client_updated';
    const CHAT_ASSIGNED = 'chat_assigned';
    const CHAT_FINISHED = 'chat_finished';
    const OFFLINE_MESSAGE = 'offline_message';
    const CHAT_UPDATED = 'chat_updated';
}