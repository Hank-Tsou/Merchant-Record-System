<?php

use App\Models\Merchant;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Str;

// GET
test('Authenticated user can view merchant notes', function () {
    $user = User::factory()->create();
    $merchant = Merchant::factory()->create();

    $this->actingAs($user);
    $response = $this->get("/merchants/{$merchant->id}/notes");

    $response->assertOk();
});

test('Unauthenticated user cannot view merchant notes', function () {
    $merchant = Merchant::factory()->create();
    $response = $this->get("/merchants/{$merchant->id}/notes");

    $response->assertRedirect('/login');
});

// POST
test('Authenticated user can create note', function () {
    $user = User::factory()->create();
    $merchant = Merchant::factory()->create();

    $noteData = [
        'merchantId'   => $merchant->id,
        'uid'          => (string) Str::uuid(),
        'title'        => 'Test Note Title',
        'body'         => 'Test note body content.',
        'type'         => 'info',
        'status'       => 'open',
        'assigned_to'  => null,
    ];

    $response = $this
        ->actingAs($user)
        ->post('/notes', $noteData);

    $response
        ->assertSessionHasNoErrors()
        ->assertStatus(201);

    $this->assertDatabaseHas('notes', [
        'title'       => $noteData['title'],
        'body'        => $noteData['body'],
        'type'        => $noteData['type'],
        'status'      => $noteData['status'],
        'merchant_id' => $noteData['merchantId'],
        'created_by'  => $user->id,
    ]);
});

test('Unauthenticated user cannot create a note', function () {
    $merchant = Merchant::factory()->create();
    $response = $this->post('/notes', [
        'merchantId' => $merchant->id,
        'uid'        => (string) Str::uuid(),
        'title'      => 'Unauthorized note',
        'body'       => 'Should not be created',
        'type'       => 'info',
        'status'     => 'open',
        'assigned_to'=> null,
    ]);

    $response->assertRedirect('/login');
});

// PUT
test('Authorized user as creator can update note', function () {
    $user = User::factory()->create();
    $note = Note::factory()->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->putJson(route('notes.update', $note), [
            'title' => 'Updated Title',
            'body' => 'Updated body',
        ])
        ->assertOk();

    $this->assertDatabaseHas('notes', [
        'id' => $note->id,
        'title' => 'Updated Title',
    ]);
});

test('Authorized user not creator cannot update note', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $note = Note::factory()->create(['created_by' => $otherUser->id]);

    $this->actingAs($user)
        ->putJson(route('notes.update', $note), [
            'title' => 'Updated Title',
            'body' => 'Updated body',
        ])
        ->assertStatus(403)
        ->assertJson(['message' => 'You are not allowed to update this note.']);
});

test('Unauthenticated user cannot update a note', function () {
    $note = Note::factory()->create();

    $response = $this->put("/notes/{$note->id}", [
        'title' => 'New Title',
        'body'  => 'Updated content',
    ]);

    $response->assertRedirect('/login');
});

// DELETE
test('Authorized user as creator can delete note', function () {
    $user = User::factory()->create();
    $note = Note::factory()->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->deleteJson(route('notes.destroy', $note))
        ->assertNoContent();

    $this->assertDatabaseMissing('notes', ['id' => $note->id]);
});

test('Authorized user not creator cannot delete note', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $note = Note::factory()->create(['created_by' => $otherUser->id]);

    $this->actingAs($user)
        ->deleteJson(route('notes.destroy', $note))
        ->assertStatus(403)
        ->assertJson(['message' => 'You are not allowed to delete this note.']);
});

test('Unauthenticated user cannot delete a note', function () {
    $note = Note::factory()->create();

    $response = $this->delete("/notes/{$note->id}");

    $response->assertRedirect('/login');
});
