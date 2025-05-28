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
                <label for="customerFile" class="form-label">Tên File</label>
                <input type="text" id="customerFile" class="form-control mb-3" placeholder="Nhập tên file" required>
                <div class="mb-3">
                    <label for="documentType" class="form-label">Loại tài liệu</label>
                    <select id="documentType" class="form-select" required>
                        <option value="pdf">PDF</option>
                        <option value="docx">Word</option>
                        <option value="xlsx">Excel</option>
                        <option value="csv">CSV</option>
                        <option value="jpg">Hình ảnh</option>
                    </select>
                </div>
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

<script>
    const contractAddress = "0x5FbDB2315678afecb367f032d93F642f64180aa3";
    const contractABI = [{
            "anonymous": false,
            "inputs": [{
                    "indexed": false,
                    "internalType": "string",
                    "name": "ipfsHash",
                    "type": "string"
                },
                {
                    "indexed": false,
                    "internalType": "address",
                    "name": "uploader",
                    "type": "address"
                },
                {
                    "indexed": false,
                    "internalType": "uint256",
                    "name": "timestamp",
                    "type": "uint256"
                }
            ],
            "name": "DocumentUploaded",
            "type": "event"
        },
        {
            "inputs": [{
                "internalType": "uint256",
                "name": "",
                "type": "uint256"
            }],
            "name": "documents",
            "outputs": [{
                    "internalType": "string",
                    "name": "ipfsHash",
                    "type": "string"
                },
                {
                    "internalType": "address",
                    "name": "uploader",
                    "type": "address"
                },
                {
                    "internalType": "uint256",
                    "name": "timestamp",
                    "type": "uint256"
                }
            ],
            "stateMutability": "view",
            "type": "function"
        },
        {
            "inputs": [{
                "internalType": "uint256",
                "name": "index",
                "type": "uint256"
            }],
            "name": "getDocument",
            "outputs": [{
                    "internalType": "string",
                    "name": "",
                    "type": "string"
                },
                {
                    "internalType": "address",
                    "name": "",
                    "type": "address"
                },
                {
                    "internalType": "uint256",
                    "name": "",
                    "type": "uint256"
                }
            ],
            "stateMutability": "view",
            "type": "function"
        },
        {
            "inputs": [],
            "name": "totalDocuments",
            "outputs": [{
                "internalType": "uint256",
                "name": "",
                "type": "uint256"
            }],
            "stateMutability": "view",
            "type": "function"
        },
        {
            "inputs": [{
                "internalType": "string",
                "name": "_ipfsHash",
                "type": "string"
            }],
            "name": "uploadDocument",
            "outputs": [],
            "stateMutability": "nonpayable",
            "type": "function"
        }


    ];

    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();

        let file = $('#fileInput')[0].files[0];
        let customFileName = $('#customFileName').val();
        let documentType = $('#documentType').val();
        if (!file) {
            alert("Please select a file.");
            return;
        }

        let formData = new FormData();
        formData.append('file', file);
        formData.append('file_name', customFileName);
        formData.append('document_type', documentType);

        $.ajax({
            url: '/api/v1/document',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                showLoadingSpinner();
            },
            success: function(response) {
                console.log(response);
                hideLoadingSpinner();
                if (response.data.original.cid) {
                    $('#ipfsHash').text(response.data.original.cid); // Hiển thị CID
                    $('#cidContainer').removeClass('d-none'); // Hiển thị container CID
                    const message = response.data.original.message || "Upload File successfully!";
                    notification("success", message);
                } else {
                    notification("error", "Upload failed, please try again.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                hideLoadingSpinner();
                notification("error", "Upload failed, please try again.");
            }
        });
    });

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
            await ethereum.request({
                method: 'eth_requestAccounts'
            });

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
