<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Satu thread support per customer. Customer pemilik thread
// + semua staff (admin/owner) boleh join. Customer lain ditolak.
Broadcast::channel('support.{customerId}', function ($user, $customerId) {
    if ((int) $user->id === (int) $customerId) {
        return true;
    }

    return $user->isAdmin() || $user->isOwner();
});
