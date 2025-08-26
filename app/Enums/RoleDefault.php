<?php

namespace App\Enums;

enum RoleDefault : string
{
    case Contributor = 'Kontributor';
    case PublicationAdministrator = 'Admin Publikasi';
    case PublicInformationAdministrator = 'Admin Informasi Publik';
    
    case EmployeeAffair = 'Kepegawaian';
    case Roster = 'Roster / Pengatur Role Permission';
}
