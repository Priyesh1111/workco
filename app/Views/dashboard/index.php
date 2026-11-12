<?php $title = 'Dashboard'; ?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <h2 class="mb-4">Dashboard</h2>


    <div class="container">
        <div class="row g-3"> <!-- 'g-3' adds clean spacing between the cards -->

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">total invoice</h5>
                        <p class="card-text">First card content.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">total amount</h5>
                        <p class="card-text">Second card content.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">unpaid invoice</h5>
                        <p class="card-text">Third card content.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <h2 class="mb-4">Invoices</h2>
    <table class="table mt-3">
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">name</th>
                <th scope="col">email</th>
                <th scope="col">phone</th>
                <th scope="col">address</th>
                <th scope="col">Added at</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td scope="col"></td>
                <td scope="col"></td>
                <td scope="col"></td>
                <td scope="col"></td>
                <td scope="col"></td>
                <td scope="col"></td>
                <td scope="col"></td>
                <td scope="col"></td>
            </tr>
        </tbody>
    </table>


</div>

<?= $this->endSection() ?>