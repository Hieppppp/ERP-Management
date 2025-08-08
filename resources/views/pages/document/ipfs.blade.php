<!-- @extends('layouts/main')
@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.document.management') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('document') }}">{{ __('translation.menu.document') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.create') }}</li>
            </ol>
        </div>
    </div>
   <div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Upload File lên IPFS</h4>
        </div>
        <div class="card-body">
            <form id="uploadForm" class="d-flex flex-column align-items-center">
                @csrf
                <div class="mb-3">
                    <input type="file" id="fileInput" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Upload</button>
            </form>
            <div class="mt-3 text-center">
                <p id="result" class="text-success fw-bold"></p>
                <div id="previewContainer" class="mt-2">
                    <img id="filePreview" class="img-fluid d-none" style="max-width: 200px; border-radius: 10px;">
                    <iframe id="pdfPreview" class="d-none" style="width: 100%; height: 300px; border: none;"></iframe>
                    <p id="fileInfo" class="fw-bold"></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endSection()
@section('scripts')
    <script src="{{ asset('assets/js/page/document/document.create.js') }}"></script>
@endSection() -->
@extends('layouts/main')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Upload File lên IPFS & Lưu CID vào Blockchain</h4>
        </div>
        <div class="card-body">
            <form id="uploadForm">
                @csrf
                <input type="file" id="fileInput" class="form-control mb-3" required>
                <button type="submit" class="btn btn-success">Upload to IPFS</button>
            </form>

            <div id="cidContainer" class="mt-4 d-none">
                <h5>IPFS CID:</h5>
                <p id="ipfsHash" class="fw-bold text-primary"></p>
                <button id="saveToBlockchain" class="btn btn-warning">Save CID to Blockchain</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ethers.io/lib/ethers-5.7.2.umd.min.js"></script>
<script>
    const contractAddress = '0xd9145CCE52D386f254917e481eB44e9943F39138'; // Example Address
    const contractABI = [
        "function uploadDocument(string memory _ipfsHash) public",
        "function totalDocuments() public view returns (uint256)",
        "event DocumentUploaded(string ipfsHash, address uploader, uint256 timestamp)"
    ];

    // Upload to IPFS via API (Laravel backend)
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();

        let file = $('#fileInput')[0].files[0];
        if (!file) {
            alert("Please select a file.");
            return;
        }

        let formData = new FormData();
        formData.append('file', file);

        $.ajax({
            url: '/api/v1/document', // Laravel API route (handle IPFS upload)
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.cid) {
                    $('#ipfsHash').text(response.cid);
                    $('#cidContainer').removeClass('d-none');
                    alert('Upload IPFS success!');
                } else {
                    alert('Upload failed');
                }
            },
            error: function() {
                alert('Upload failed');
            }
        });
    });

    // Save CID to Blockchain
    $('#saveToBlockchain').on('click', async function() {
        const cid = $('#ipfsHash').text();
        if (!cid) {
            alert('CID not found');
            return;
        }

        if (typeof window.ethereum === 'undefined') {
            alert('Please install MetaMask');
            return;
        }

        try {
            // MetaMask connect
            await ethereum.request({ method: 'eth_requestAccounts' });

            const provider = new ethers.providers.Web3Provider(window.ethereum);
            const signer = provider.getSigner();
            const contract = new ethers.Contract(contractAddress, contractABI, signer);

            const tx = await contract.uploadDocument(cid);
            console.log('Transaction hash:', tx.hash);

            await tx.wait();
            alert('CID saved to Blockchain successfully!');
        } catch (error) {
            console.error(error);
            alert('Failed to save CID');
        }
    });
</script>
@endsection

