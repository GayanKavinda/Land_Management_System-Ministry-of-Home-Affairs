@extends('layouts.app')

<div class="div">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <br/>
    <br/>
    <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link-custom nav-link-view-estates" style="text-decoration: none; color: #fff; font-weight: bold; padding: 10px 20px; background-color: #007bff; border: 1px solid #0056b3; border-radius: 5px; transition: background-color 0.3s, color 0.3s;" href="{{ route('showEstates') }}">View Estates</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link-custom nav-link-create-estate" style="text-decoration: none; color: #fff; font-weight: bold; padding: 10px 20px; background-color: #ff5733; border: 1px solid #e64a19; border-radius: 5px; transition: background-color 0.3s, color 0.3s;" href="{{ route('addEstateData') }}">Create Estate</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
</div>
