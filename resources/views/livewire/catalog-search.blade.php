<div>
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="shop__sidebar">
                <div class="shop__sidebar__search">
                    <div style="position: relative;">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search" 
                               placeholder="Buscar productos..."
                               style="width: 100%; padding: 12px 40px 12px 15px; border: 2px solid #eee; border-radius: 25px; font-size: 14px;">
                        <span class="icon_search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #999;"></span>
                    </div>
                    @if($search)
                        <div style="margin-top: 10px;">
                            <small class="text-muted">Buscando: "{{ $search }}"</small>
                            <button wire:click="clearFilters" class="btn btn-sm btn-link text-danger" style="padding: 0; margin-left: 10px;">
                                <i class="fa fa-times"></i> Limpiar
                            </button>
                        </div>
                    @endif
                </div>
                
                <!-- Categorías colapsadas para móvil -->
                <div class="shop__sidebar__accordion" style="margin-top: 15px;">
                    <div class="accordion" id="accordionExample">
                        @foreach($categories as $category)
                            <div class="card" style="border: none; margin-bottom: 0;">
                                <div class="card-heading" style="background: #f8f8f8; padding: 10px 15px; border-radius: 5px; margin-bottom: 5px;">
                                    <a data-toggle="collapse" data-target="#collapse{{ $category->id }}" 
                                       style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: #111;">
                                        {{ $category->name }}
                                    </a>
                                </div>
                                <div id="collapse{{ $category->id }}" class="collapse" data-parent="#accordionExample">
                                    <div class="card-body" style="padding: 5px 0 10px 10px;">
                                        <div class="shop__sidebar__categories">
                                            <ul style="list-style: none; padding: 0; margin: 0;">
                                                @foreach($category->children as $child)
                                                    <li style="margin-bottom: 5px;">
                                                        <a href="#" wire:click.prevent="filterByCategory({{ $child->id }})" 
                                                           style="font-size: 13px; padding: 5px 10px; display: inline-block; border-radius: 15px; transition: all 0.3s;
                                                           {{ $categoryId == $child->id ? 'background: #111; color: #fff;' : 'color: #666;' }}">
                                                            {{ $child->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($categoryId)
                        <div style="margin-top: 10px;">
                            <button wire:click="clearFilters" 
                                    style="width: 100%; padding: 8px; border: none; background: #e53637; color: #fff; border-radius: 20px; font-size: 12px; cursor: pointer;">
                                <i class="fa fa-times"></i> Limpiar Filtros
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="shop__product__option">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="shop__product__option__left">
                            <p>Mostrando {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} de {{ $products->total() }} resultados</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="shop__product__option__right">
                            <p>Ordenar por Precio:</p>
                            <select wire:model.live="sortBy" style="border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px;">
                                <option value="default">Por defecto</option>
                                <option value="price_asc">Bajo a Alto</option>
                                <option value="price_desc">Alto a Bajo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading indicator -->
            <div wire:loading class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Buscando...</span>
                </div>
            </div>

            <div wire:loading.remove class="row">
                @forelse($products as $product)
                    <div class="col-lg-4 col-md-6 col-6">
                        <div class="product__item">
                            <div class="product__item__pic set-bg" data-setbg="{{ asset($product->image_path) }}" style="background-image: url('{{ asset($product->image_path) }}');">
                                <ul class="product__hover">
                                    <li><a href="#"><img src="{{ asset('assets/malefashion/img/icon/heart.png') }}" alt=""></a></li>
                                    <li><a href="{{ route('product.show', $product->id) }}"><img src="{{ asset('assets/malefashion/img/icon/search.png') }}" alt=""></a></li>
                                </ul>
                            </div>
                            <div class="product__item__text">
                                <h6>{{ $product->name }}</h6>
                                <a href="{{ route('product.show', $product->id) }}" class="add-cart">+ Ver Detalles</a>
                                <h5>${{ number_format($product->price, 2) }}</h5>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h5 class="text-muted">No se encontraron productos</h5>
                        @if($search || $categoryId)
                            <button wire:click="clearFilters" class="btn btn-outline-primary mt-3">
                                Ver todos los productos
                            </button>
                        @endif
                    </div>
                @endforelse
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="product__pagination">
                        {{ $products->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
