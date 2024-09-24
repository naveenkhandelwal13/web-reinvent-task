<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{!! csrf_token() !!}">

        <title>WebReinvent Test</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .apiMessage{position: absolute;}
        .row_ajax_loader {position: absolute;height: 100%;width: 100%;background-color: rgba(255,255,255,.8);left: 0;top: 0;}
    </style>

    </head>
    <body>
        
        <div class="container h-100 d-flex align-items-center justify-content-center">

            <div class="row">
                
                <div class="col-12 mx-auto">
                    
                            <form id="to-do-form" class="row was-validated d-flex align-items-center justify-content-center">

                                <div class="col-12">
                                    <h3 class="pb-4 text-primary float-start">PHP Simple To Do List</h3>
                                </div>
                               
                                <div class="col-5">
                                    <input type="text" class="form-control" id="taskName" placeholder="Enter Task" name="task_name" required>
                                    <span class="float-start apiMessage"></span>

                                </div>
                                <div class="col-5">
                                    <button type="submit" class="btn btn-primary add_task">Add Task</button>

                                    <button type="submit" class="btn btn-primary with-trashed">Show All</button>

                                    <button type="submit" class="btn btn-primary only-trashed">Trashed Only</button>


                                </div>
                                
                                
                            </form>
                    
                </div>

                <div class="col-9 mx-auto mt-4">
                    <div class="row">
                        <div class="col-12 mx-auto position-relative">


                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Task</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="table-data">
                                    
                                </tbody>
                            </table>

                            <div class="row_ajax_loader" style="display: none;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
    
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <script>

            $(document).ready(function() {


                $(document).on('click','.add_task',function(e){

                    e.preventDefault();

                    if ($('#taskName').val().length < 1) {

                        return false;
                        
                    }
                    
                    var tokenVal = $('meta[name="csrf-token"]').attr('content');
                    var formData = new FormData($("#to-do-form")[0]); 
                    // formData.append("action", 'saveDisposition');
                    formData.append("_token", tokenVal);
                    $.ajax({

                        url : '/task/create',
                        type : "POST",
                        data : formData,
                        processData: false,
                        contentType: false,
                        dataType:'JSON',
                        beforeSend: function(){  
                            $('.row_ajax_loader').show();
                        },
                        complete: function(){   
                            $('.row_ajax_loader').hide();
                        },
                        success: function(response) {
                            $('#table-data').html(response.data);
                            refreshData();

                        },
                        error: function(xhr) {

                            if (xhr.status === 409) {
                                $('.apiMessage').html('<p class="text-danger fw-bold">Duplicate Entry</p>');
                            }

                        }

                    });
                    
                });


                $(document).on('click','.update-button',function(){
                    alterData(this);
                });

                $(document).on('click','.trash-button',function(){
                    if (confirm("Are you sure you want to Move this task into Trash?")) {
                        alterData(this);
                    }
                });


                
                $(document).on('click','.only-trashed',function(e){
                    e.preventDefault();
                    var queryString = {'list_type':'only_trashed'};
                    customAction(queryString);
                })


                $(document).on('click','.with-trashed',function(e){
                    e.preventDefault();
                    var queryString = {'list_type':'with_trashed'};
                    customAction(queryString);
                })

                function customAction(queryString){
                    var tokenVal = $('meta[name="csrf-token"]').attr('content');

                    queryString["_token"] = tokenVal;


                    $.ajax({
                        url : 'task/list',
                        type : 'GET',
                        data : queryString,
                        dataType:'JSON',
                        beforeSend: function(){  
                            $('.row_ajax_loader').show();
                        },
                        complete: function(){   
                            $('.row_ajax_loader').hide();
                        },
                        success: function(response) { 
                            $('#table-data').html(response.data);

                        }

                    });

                }

                
                function alterData(elemObj){
                    var dataAction = $(elemObj).attr('data-action');

                    var action = dataAction == 1 ? 'update' : 'delete';

                    var id = $(elemObj).attr('data-id');

                    var tokenVal = $('meta[name="csrf-token"]').attr('content');

                    var queryString = {'_token':tokenVal,'status':dataAction};

                    $.ajax({
                        url : 'task/'+action+'/'+id,
                        type : 'POST',
                        data : queryString,
                        dataType:'JSON',
                        beforeSend: function(){  
                            $('.row_ajax_loader').show();
                        },
                        complete: function(){   
                            $('.row_ajax_loader').hide();
                        },
                        success: function(response) { 
                            $('#table-data').html(response.data);
                        }

                    })


                }


                function refreshData(){
                    $('#to-do-form')[0].reset();
                    $('.apiMessage').html('');
                }


            });
        </script>
    </body>
</html>
