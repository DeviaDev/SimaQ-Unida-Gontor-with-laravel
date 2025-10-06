@extends('layouts/app')

@section('content')
     <!-- Page Heading -->
     <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-user mr-2"></i>
        {{ $title }}
    </h1>

    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-center justify-content-xl-between">
           
                <div class="mb-1 mr-2">
                <a href="{{ route('userCreate') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Add User
                </a>
            </div>
            <div class="mr-1">
                <a href="" class="btn btn-sm btn-success">
                    <i class="fas fa-file-excel mr-2"></i>
                    Export Excel
                </a>
            
                <a href="" class="btn btn-sm btn-danger">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Export PDF
                </a>
            </div>
         
            
        </div>
        <div class="card-body">
           <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-white">
                                        <tr class="text-center">
                                            <th>id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                       
                                       
                                    </tbody>
                                </table>
            </div> 
        </div>
    </div>
@endsection