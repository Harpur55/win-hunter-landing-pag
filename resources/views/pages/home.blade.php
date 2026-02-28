@extends('layouts.app')

@section('title', 'Home - Win Hunter')

@section('content')

    @include('sections.hero') 
     @include('sections.gallery')  
    @include('sections.about')
    @include('partials.unit')
   
    @include('partials.coach')
    @include('partials.service')
    @include('partials.jadwal')
    @include('partials.contact')

@endsection