@extends('admin.layouts.app')

@section('content')
  <div class="container">
    <x-admin.profile-card 
      :user="$user" 
      :backUrl="route('admin.users.index')" 
      :addressesCount="$user->addresses->count()" 
    />

    <div class="mt-6" id="addresses-section">
      <x-admin.address-list :addresses="$user->addresses" />
    </div>
  </div>
@endsection
