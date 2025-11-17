- Contoh Tombol Solid (Default):

<x-button type="submit">
    [ DISPATCH BROADCAST ]
</x-button>

- Tombol dengan teks biasa
<x-button>
    Approve
</x-button>


- Contoh Tombol Outline:

<x-button variant="outline">
    [ VIEW ]
</x-button>


- Contoh Tombol Secondary (untuk Batal/Tutup):

<x-button variant="secondary" @click="isModalOpen = false">
    CLOSE
</x-button>


- Contoh Tombol sebagai Link:

<x-button href="{{ route('dashboard') }}">
    Go to Dashboard
</x-button>


- Contoh Tombol Ukuran Kecil (Small):

<x-button size="sm">
    [ COPY ]
</x-button>