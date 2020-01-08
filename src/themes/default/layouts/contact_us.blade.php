@extends('themes.default.partials.default')

@section("content")
    
    <div class="container">
    
    <h2>{{$page->getContent('title')}}</h2>

    <form class="form" action="/modules/{{$page->id}}/contact_us/send" method="POST">
        {{csrf_field()}}
        <div class="form-group">
            <label class="form-label">Name</label>
            <input class="form-control" name='name' placeholder="Your name" value="{{old('name')}}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name='email' class="form-control" placeholder="Your email"
                   value="{{old('email')}}"
                   required>
        </div>
        <div class="form-group">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" required>{{old('message')}}</textarea>
        </div>
        <div class="form-group">
            <button class="btn btn-success" type="submit">Send</button>
        </div>
    
    </form>
    </div>

@endsection
