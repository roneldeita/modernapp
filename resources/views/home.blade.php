@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Post Something</div>

                <div class="panel-body">
                    <form name="create-post">
                        {{ csrf_field() }}
                        <div class="form-group">
                           <div class="input-group input-group-sm">
                                <span class="input-group-addon text-primary" id="category">About</span>
                                <select class="form-control" name="postcategory_id" style="width:135px" aria-describeby="category">
                                    <option value="">Select an option</option>
                                    @foreach($postcategories as $id=>$postcategory)
                                        <option value="{{ $id }}">{{ $postcategory }}</option>
                                    @endforeach
                                </select>
                           </div> 
                        </div>
                        <div class="form-group">
                             <textarea class="form-control" rows="4" name="body"></textarea>
                        </div>
                        <div class="form-group">
                             <button class="btn btn-sm btn-primary pull-right" name="post">Post</button>
                        </div>
                        <div class="notif-msg"></div>
                    </form>
                </div>
            </div>

            <div id="posts"></div>

        </div>
    </div>
</div>
@endsection

@section('scripts')

    <script type="text/javascript">
        var postcategory    = $('select[name=post-category]');
        var body            = $('textarea[name=body]');
        var postBtn         = $('button[name=post]');
        var form            = $('form[name=create-post]');
        var notif           = $('div.notif-msg');
        //posts
        var panel           =$('div#posts');

        form.on('submit', function(e){
            e.preventDefault();
        })

        $(function(){
            //load posts
            loadPosts();

            //create post
            postBtn.on('click', function(){

                $.ajax({
                    type: "POST",
                    url: "{{ url('/admincontrol/post/create') }}",
                    data : form.serializeArray(),
                    dataType: "JSON",
                    beforeSend: function(){
                        cleanNotif();
                    },
                    success: function(data){
                        cleanForm();
                        loadPosts();
                    },
                    error: function(xhr, status, error){

                        var errors = $.parseJSON(xhr.responseText);

                        notif.append($('<ul></ul>', {name:"error-msgs", class:"text-danger"}));

                        $.each(errors, function(key, value){

                            $('ul[name=error-msgs]').append($('<li></li>',{text: value}));

                        });

                
                    }

                });

            });

            function loadPosts(){

                console.log('triggered');

                $.ajax({
                    type:"GET",
                    url: "{{ url('admincontrol/post/data') }}",
                    data:{
                        "_token":"{{ csrf_token() }}"
                    },
                    dataType:"JSON",
                    beforeSend: function(){
                        cleanForm();
                    },
                    success: function(data){

                        console.log(data);
                        
                        $.each(data, function(key, value){

                            panel.append(
                                $('<div></div>', { class:"panel panel-default"}).append(
                                    $('<div></div>', {class:"panel-body"}).append(
                                        $('<div></div>').append(
                                            $('<span></span>',{ class:"fa fa-user-circle", style:"padding: 0 3px 0 0"}),
                                            $('<a></a>',{ text:value['owner'], href:"javascript:;" }),
                                            $('<span></span>',{ text:value['created'], style:"font-size:12px", class:"text-muted pull-right"})
                                        ),
                                        $('<div></div>', {text:value['body'], style:"margin-top:10px"})
                                    )
                                )
                            );

                        });

                    }

                });

            }

            function cleanForm(){
                panel.empty();
                console.log(form);
                form.trigger('reset');

            }

            function cleanNotif(){
                notif.empty();
            }
            

        });
        
    </script>

@endsection
