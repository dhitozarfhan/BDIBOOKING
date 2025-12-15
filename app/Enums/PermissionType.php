<?php

namespace App\Enums;

enum PermissionType : string
{
    case RolePermission = 'kelola hak akses';
    case Employee = 'kelola pegawai';
    case News = 'kelola berita';
    case Gallery = 'kelola berita-foto / galeri';
    case Page = 'kelola halaman';
    case Slideshow = 'kelola slideshow';
    case PublicInformation = 'kelola informasi publik';
    case ArticleCategory = 'kelola kategori artikel';
    case PublicInformationCategory = 'kelola kategori informasi publik';
    case Tag = 'kelola tag';
    case Menu = 'kelola menu';
    case Archives = 'kelola arsip';
    case Finance = 'kelola keuangan';
    case Complaints = 'kelola pengaduan';
    case GratificationResponses = 'tanggapi gratifikasi';
    case WBSResponses = 'tanggapi wbs';
    case QuestionResponses = 'tanggapi pertanyaan masyarakat';
    case InformationRequestResponses = 'tanggapi permintaan informasi';
    case ManageDisposition = 'kelola disposisi';

}
