@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Ticket</div>

                    <div class="row m-3">
                        <h3>
                            Subject:
                            {{$ticket->subject}}
                        </h3>

                        <p>
                          Email:  {{$ticket->email}}
                        </p>

                        <p>
                            Content:
                            {{$ticket->content}}
                        </p>
                        <p>
                            Replies:
                            #Todo show replies
                        </p>
                    </div>



                    <div class="card-body">
                        <form method="POST" action="{{ route('replies.store', $ticket->id) }}">
                            @csrf
                            @method('post')

                            <div class="row mb-3">
                                <label for="content" class="col-md-4 col-form-label text-md-end">reply</label>

                                <div class="col-md-6">
                                    <textarea id="reply" class="form-control @error('content') is-invalid @enderror" name="reply" required>
                                        {{ old('reply') }}
                                    </textarea>
                                    @error('reply')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
