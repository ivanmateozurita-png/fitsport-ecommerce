<div class="livewire-search-wrapper" style="position: relative;">
    <input 
        type="text" 
        wire:model.live.debounce.300ms="search" 
        class="form-control search-input"
        placeholder="Buscar productos..."
        style="border-radius: 20px; padding: 10px 20px; border: 1px solid #ddd;"
    >
    
    @if(count($products) > 0)
        <div class="search-results" style="
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-top: 5px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
        ">
            @foreach($products as $product)
                <a href="{{ route('product.show', $product->id) }}" 
                   style="display: block; padding: 10px 15px; text-decoration: none; color: #333; border-bottom: 1px solid #eee;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        @if($product->image_path)
                            <img src="{{ asset($product->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;">
                        @endif
                        <div>
                            <strong>{{ $product->name }}</strong>
                            <div style="font-size: 12px; color: #e53637; font-weight: bold;">
                                ${{ number_format($product->price, 2) }}
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @elseif(strlen($search) >= 2)
        <div class="search-results" style="
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-top: 5px;
            padding: 15px;
            text-align: center;
            color: #666;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            z-index: 1000;
        ">
            No se encontraron productos para "{{ $search }}"
        </div>
    @endif
</div>
