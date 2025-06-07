{{-- Saved Queries List --}}
<div id="saved-queries" class="space-y-2">
    @foreach ($pastQueries as $query)
        <div class="query-item flex items-center justify-between p-2 border rounded" data-id="{{ $query->id }}">
            <div>
                <div class="font-semibold">{{ $query->question }}</div>
                <div class="text-xs text-gray-500">{{ $query->created_at->diffForHumans() }}</div>
            </div>
            <button class="toggle-favorite text-xl" data-id="{{ $query->id }}">
                @if ($query->favorited)
                    ⭐
                @else
                    ☆
                @endif
            </button>
        </div>
    @endforeach
</div>

{{-- Include this in your Blade footer --}}
<script>
    document.querySelectorAll('.toggle-favorite').forEach(button => {
        button.addEventListener('click', async (e) => {
            const id = button.dataset.id;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch(`/queries/${id}/favorite`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                }
            });

            if (res.ok) {
                const btn = e.currentTarget;
                const current = btn.textContent.trim();
                btn.textContent = (current === '☆') ? '⭐' : '☆';

                // Re-sort list based on star
                const container = document.getElementById('saved-queries');
                const items = Array.from(container.children);

                items.sort((a, b) => {
                    const aStar = a.querySelector('.toggle-favorite').textContent.trim() === '⭐' ? 1 : 0;
                    const bStar = b.querySelector('.toggle-favorite').textContent.trim() === '⭐' ? 1 : 0;
                    return bStar - aStar;
                });

                items.forEach(item => container.appendChild(item));
            }
        });
    });

    fetch(`/query-dashboard/favorite/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
        }
    });

</script>
<script>
    document.querySelectorAll('.delete-query').forEach(button => {
        button.addEventListener('click', async () => {
            if (!confirm('Are you sure you want to delete this query?')) return;

            const id = button.dataset.id;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch(`/query-dashboard/delete/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                // Remove the table row
                button.closest('tr').remove();
            } else {
                console.error('Delete query failed.');
            }
        });
    });
</script>
