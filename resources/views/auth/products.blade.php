@extends('layouts.backend.master')

@section('page_title', 'Browse Products')
@section('header_height', '85px')
@section('header_padding', '0 2.5rem')

@section('content')
<div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 3rem; border-radius: 1.5rem; margin-bottom: 2.5rem; position: relative; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
    <div style="position: relative; z-index: 2;">
        <h3 style="color: #fff; margin: 0; font-size: 2.25rem; font-weight: 800; letter-spacing: -1px;">Premium Product Catalog</h3>
        <p style="color: #94a3b8; font-size: 1.1rem; margin-top: 0.75rem; max-width: 600px;">Experience the best-in-class support for our enterprise-grade solutions. Select a product to view specialized support options.</p>
    </div>
    <!-- Abstract background decoration -->
    <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(99, 102, 241, 0.2); border-radius: 50%; filter: blur(60px);"></div>
    <div style="position: absolute; bottom: -20px; right: 100px; width: 150px; height: 150px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; filter: blur(40px);"></div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 2rem;">
    @forelse($products as $product)
    <div class="product-card">
        <div class="card-visual">
            <div class="category-pill">{{ $product->category->name ?? 'Enterprise' }}</div>
            <div class="price-pill">${{ number_format($product->price, 2) }}</div>
        </div>
        <div class="card-body">
            <h4 class="product-name">{{ $product->name }}</h4>
            <div class="subcategory-label"><i class="fas fa-layer-group"></i> {{ $product->subCategory->name ?? 'Standard Edition' }}</div>
            <p class="product-desc">{{ Str::limit($product->description, 100) }}</p>
            
            <div class="card-footer">
                <a href="{{ route('user.tickets.create', ['product_id' => $product->id]) }}" class="support-btn">
                    <span>Raise Support Ticket</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 5rem; background: #fff; border-radius: 1.5rem; border: 2px dashed #e2e8f0;">
        <div style="width: 80px; height: 80px; background: #f1f5f9; color: #cbd5e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem;">
            <i class="fas fa-search"></i>
        </div>
        <h4 style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">Catalog Under Maintenance</h4>
        <p style="color: #64748b; margin-top: 0.5rem;">We are currently updating our products. Please check back shortly.</p>
    </div>
    @endforelse
</div>

<div style="margin-top: 3rem; display: flex; justify-content: center;">
    {{ $products->links() }}
</div>

<style>
    .product-card { 
        background: #fff; 
        border-radius: 1.25rem; 
        overflow: hidden; 
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
        border: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
    }
    
    .product-card:hover { 
        transform: translateY(-10px); 
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08); 
        border-color: #e0e7ff;
    }

    .card-visual {
        height: 140px;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
    }

    .category-pill {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        padding: 0.4rem 1rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: #475569;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .price-pill {
        background: var(--primary-color);
        color: #fff;
        padding: 0.4rem 1rem;
        border-radius: 2rem;
        font-weight: 800;
        font-size: 0.95rem;
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
    }

    .card-body { padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column; }
    
    .product-name { 
        font-size: 1.35rem; 
        font-weight: 700; 
        color: #0f172a; 
        margin: 0 0 0.5rem 0;
        line-height: 1.2;
    }

    .subcategory-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .product-desc { 
        color: #475569; 
        font-size: 0.925rem; 
        line-height: 1.6; 
        margin-bottom: 1.5rem;
    }

    .card-footer { margin-top: auto; }

    .support-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
        color: #1e293b;
        text-decoration: none;
        padding: 1rem 1.25rem;
        border-radius: 1rem;
        font-weight: 700;
        font-size: 0.9rem;
        border: 1px solid #e2e8f0;
        transition: 0.3s;
    }

    .support-btn:hover {
        background: var(--primary-color);
        color: #fff;
        border-color: var(--primary-color);
        transform: scale(1.02);
    }

    .support-btn i { transition: 0.3s transform; }
    .support-btn:hover i { transform: translateX(5px); }
</style>
@endsection
