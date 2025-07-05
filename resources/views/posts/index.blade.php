@extends('layouts.app')

@section('title', 'Manage Posts')

@section('content')
<style>
        #newCommentText:focus {
    outline: none;
    box-shadow: none;
}
#commentForm {
    transition: box-shadow 0.2s ease-in-out;
}
#commentForm:focus-within {
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
}

#modalComments::-webkit-scrollbar {
  display: none;                   /* Chrome, Safari, Edge */
}
#modalComments {
    max-height: 1000px;  /* Ajusta según necesidad */
    overflow-y: auto;   /* Muestra scroll solo si es necesario */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE/Edge */
    padding-right: 5px; /* Evita que el contenido toque los bordes */
}

#modalComments::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}
#newCommentText {
    background-color: #f0f2f5;
    border-radius: 20px;
    padding: 10px 15px;
    font-size: 14px;
    border: none;
    box-shadow: none;
}

#newCommentText::placeholder {
    color: #999;
}

#newCommentText:focus {
    background-color: #fff;
    outline: none;
    box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.2);
}

#commentForm {
    border-radius: 30px;
    padding: 8px
     12px;
}
#commentsModal .modal-body { /* Ajusta según tu estructura HTML */
    overflow: hidden;
    border-radius: 10px; /* Coordina con tus estilos */
}
</style>
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-newspaper"></i> Manage Posts</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        {{-- Real Posts --}}
@forelse($posts as $post)
<div class="col-md-6 col-lg-4 mb-4">
    <div class="card h-100 shadow-sm">
        @if($post->image)
<img src="{{ Storage::url($post->image) }}" class="card-img-top" alt="Post Image">
        @endif
        <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ $post->title }}</h5>
            <p class="text-muted small mb-2">
                <i class="fas fa-user"></i> {{ $post->author }} &middot;
                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($post->created_at)->format('M d, Y H:i') }}
            </p>
            <p class="card-text">{{ Str::limit($post->content, 100) }}</p>

            <hr class="my-2">

            {{-- Reactions with like animation --}}
            <div class="d-flex justify-content-between align-items-center mt-auto">
                <div>
                    <button class="btn btn-sm btn-outline-primary btn-lg me-2 like-btn" data-id="{{ $post->_id }}">
                        <i class="fas fa-thumbs-up fa-lg"></i> <span class="like-count">0</span>
                    </button>
                    <button class="btn btn-sm btn-outline-danger btn-lg dislike-btn" data-id="{{ $post->_id }}">
                        <i class="fas fa-thumbs-down fa-lg"></i> <span class="dislike-count">0</span>                    
                    </button>
                </div>
                <div class="d-flex align-items-center">
                    <a href="#" class="me-3 text-decoration-none text-dark open-comments" data-id="{{ $post->_id }}">
                        <i class="fas fa-comment"></i> Comentarios {{ $post->comments_count }}
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('posts.destroy', $post->_id) }}" class="mt-3"
                  onsubmit="return confirm('Are you sure you want to delete this post?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger w-100">
                    <i class="fas fa-trash-alt"></i> Delete Post
                </button>
            </form>
        </div>
    </div>
</div>
@empty
    <div class="col-12">
        <p class="text-center text-muted">No posts found.</p>
    </div>
@endforelse

    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-body d-flex flex-wrap p-0" style="max-height: 90vh;">
        
        <!-- Publicación -->
        <div class="col-md-7 p-4 border-end">
          <img id="modalImage" src="" class="img-fluid rounded mb-3" alt="Post Image">
          <h4 id="modalTitle"></h4>
          <p class="text-muted" id="modalAuthorDate"></p>
          <p id="modalContent"></p>
        </div>

<!-- Comentarios (col derecha) -->
<div class="col-md-5 d-flex flex-column p-0" style="max-height: 90vh;">

  <!-- Título -->
  <div class="px-4 pt-4 pb-2">
    <h5 class="mb-0">Comentarios</h5>
  </div>

  <!-- Lista de comentarios con scroll interno -->
  <div id="modalComments" class="flex-grow-1 px-4 overflow-auto"
       style="scrollbar-width: none; -ms-overflow-style: none; overflow-y: auto;">
    <!-- Comentarios dinámicos aquí -->
  </div>

  <!-- Caja de nuevo comentario siempre visible -->
  <div class="border-top p-3 bg-white shadow-sm">
    <form id="commentForm" data-post-id="" class="d-flex align-items-center">
      <input id="newCommentText" type="text" class="form-control border-0 me-2 shadow-none"
             placeholder="Escribe un comentario...">
      <button type="submit" class="btn btn-primary rounded-circle">
        <i class="fas fa-paper-plane"></i>
      </button>
    </form>
  </div>
</div>





          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    const likeButtons = document.querySelectorAll('.like-btn');
    const dislikeButtons = document.querySelectorAll('.dislike-btn');

    likeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const dislikeBtn = btn.parentElement.querySelector('.dislike-btn');
            const likeCountSpan = btn.querySelector('.like-count');
            const dislikeCountSpan = dislikeBtn ? dislikeBtn.querySelector('.dislike-count') : null;
            let likeCount = parseInt(likeCountSpan.textContent);
            let dislikeCount = dislikeCountSpan ? parseInt(dislikeCountSpan.textContent) : 0;

            if (btn.classList.contains('liked')) {
                // Quitar like
                btn.classList.remove('liked');
                btn.disabled = false;
                likeCountSpan.textContent = likeCount > 0 ? likeCount - 1 : 0;
            } else {
                // Poner like
                btn.classList.add('liked');
                btn.disabled = true;
                likeCountSpan.textContent = likeCount + 1;

                // Si dislike estaba activo, quitarlo y decrementar contador
                if (dislikeBtn && dislikeBtn.classList.contains('disliked')) {
                    dislikeBtn.classList.remove('disliked');
                    dislikeBtn.disabled = false;
                    if (dislikeCountSpan) {
                        dislikeCountSpan.textContent = dislikeCount > 0 ? dislikeCount - 1 : 0;
                    }
                }
            }
        });
    });

    dislikeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const likeBtn = btn.parentElement.querySelector('.like-btn');
            const likeCountSpan = likeBtn ? likeBtn.querySelector('.like-count') : null;
            const dislikeCountSpan = btn.querySelector('.dislike-count');
            let likeCount = likeCountSpan ? parseInt(likeCountSpan.textContent) : 0;
            let dislikeCount = parseInt(dislikeCountSpan.textContent);

            if (btn.classList.contains('disliked')) {
                // Quitar dislike
                btn.classList.remove('disliked');
                btn.disabled = false;
                dislikeCountSpan.textContent = dislikeCount > 0 ? dislikeCount - 1 : 0;
            } else {
                // Poner dislike
                btn.classList.add('disliked');
                btn.disabled = true;
                dislikeCountSpan.textContent = dislikeCount + 1;

                // Si like estaba activo, quitarlo y decrementar contador
                if (likeBtn && likeBtn.classList.contains('liked')) {
                    likeBtn.classList.remove('liked');
                    likeBtn.disabled = false;
                    if (likeCountSpan) {
                        likeCountSpan.textContent = likeCount > 0 ? likeCount - 1 : 0;
                    }
                }
            }
        });
    });
});
</script>
<script>
    const allPosts = @json($posts);
    const allComments = @json($comments);
    const allUsers = @json($users);
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const allPosts = @json($posts);
    const allComments = @json($comments);
    const allUsers = @json($users);
    const commentForm = document.getElementById('commentForm');

    // Listener para abrir el modal con los comentarios
    document.querySelectorAll('.open-comments').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const postId = this.dataset.id;

            // Asigna el ID del post al form dentro del modal
            commentForm.dataset.postId = postId;

            const post = allPosts.find(p =>
                p._id === postId || (p._id?.$oid && p._id.$oid === postId)
            );
            if (!post) return;

            // Imagen
            const imgSrc = post.image?.startsWith('http') ? post.image : '/storage/' + post.image;
            document.getElementById('modalImage').src = imgSrc;
            document.getElementById('modalTitle').textContent = post.title;
            document.getElementById('modalAuthorDate').textContent =
                `${post.author} · ${new Date(post.created_at).toLocaleString()}`;
            document.getElementById('modalContent').textContent = post.content;

            // Mostrar comentarios del post
            const filteredComments = allComments.filter(c =>
                c.post_id === postId || (c.post_id?.$oid && c.post_id.$oid === postId)
            );

            const commentsContainer = document.getElementById('modalComments');
            commentsContainer.innerHTML = '';

            filteredComments.forEach(comment => {
                const user = allUsers.find(u =>
                    u._id === comment.usuario_id || (u._id?.$oid && u._id.$oid === comment.usuario_id)
                );
                const userName = user?.name || 'Usuario desconocido';

                let fechaStr = 'Fecha desconocida';
                try {
                    let rawDate = comment.fecha;
                    if (typeof rawDate === 'object' && rawDate !== null) {
                        if ('$date' in rawDate) {
                            const dateValue = rawDate.$date;
                            rawDate = typeof dateValue === 'object' && '$numberLong' in dateValue
                                ? parseInt(dateValue.$numberLong, 10)
                                : dateValue;
                        }
                    }
                    const parsedDate = new Date(rawDate);
                    if (!isNaN(parsedDate)) {
                        fechaStr = parsedDate.toLocaleString('es-MX', {
                            dateStyle: 'long',
                            timeStyle: 'short',
                        });
                    }
                } catch {}

                const wrapper = document.createElement('div');
                wrapper.className = 'comment d-flex mb-3';
                wrapper.innerHTML = `
                    <img src="/images/default-avatar.png" class="rounded-circle me-3" width="40" height="40" alt="User Avatar">
                    <div>
                        <div class="bg-light rounded px-3 py-2">
                            <strong class="d-block">${userName}</strong>
                            ${comment.texto}
                        </div>
                        <small class="text-muted">${fechaStr}</small>
                    </div>
                `;
                commentsContainer.appendChild(wrapper);
            });

            const modal = new bootstrap.Modal(document.getElementById('commentsModal'));
            modal.show();
        });
    });

    // Listener para enviar comentario nuevo
    commentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('newCommentText');
        const text = input.value.trim();
        if (!text) return;

        const postId = commentForm.dataset.postId;

        fetch("{{ route('comments.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                post_id: postId,
                texto: text
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const commentsContainer = document.getElementById('modalComments');
                const wrapper = document.createElement('div');
                wrapper.className = 'comment d-flex mb-3';
                wrapper.innerHTML = `
                    <img src="/images/default-avatar.png" class="rounded-circle me-3" width="40" height="40" alt="User Avatar">
                    <div>
                        <div class="bg-light rounded px-3 py-2">
                            <strong class="d-block">${data.user_name}</strong>
                            ${text}
                        </div>
                        <small class="text-muted">${data.formatted_date}</small>
                    </div>
                `;
                commentsContainer.appendChild(wrapper);
                input.value = '';
                commentsContainer.scrollTop = commentsContainer.scrollHeight;
            }
        })
        .catch(err => {
            console.error('Error sending comment:', err);
        });
    });
});
</script>






