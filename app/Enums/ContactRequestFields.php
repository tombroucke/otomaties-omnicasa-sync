<?php

namespace Otomaties\Omnicasa\Enums;

enum ContactRequestFields
{
    case Name;
    case FirstName;
    case AddressTitle;
    case Address;
    case City;
    case Zip;
    case Email;
    case PhoneNumber;
    case ObjectID;
    case Subject;
    case Content;
    case SiteId;
    case SourceId;
    case LanguageIdFromParameter;
    case LanguageId;
    case OfficeID;
    case CustomerName;
    case CustomerPassword;
    case SecretKey;
    case WriteLogSQL;
}
