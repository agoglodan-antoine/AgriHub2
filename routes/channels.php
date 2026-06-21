<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// Canal pour les conversations de messagerie
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    // Vérifier que l'utilisateur a accès à cette conversation
    // Format: min(id1,id2)_max(id1,id2)
    $ids = explode('_', $conversationId);
    if (count($ids) === 2) {
        $userId = (int) $user->id;
        $id1 = (int) $ids[0];
        $id2 = (int) $ids[1];
        
        // L'utilisateur doit être l'un des deux participants
        return $userId === $id1 || $userId === $id2;
    }
    return false;
});

// Canal pour les notifications utilisateur (optionnel)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Canal pour les notifications en temps réel (optionnel)
Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});