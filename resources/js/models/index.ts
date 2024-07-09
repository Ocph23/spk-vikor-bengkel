export interface Kriteria{
    id:number;
    nama:string;
    variabel:string;
    keterangan:string;
    bobot:number;
    W:number;
    Max:number;
    Min:number;
}

export interface Periode{
    id:number;
    nama:string;
    keterangan:string;
    status:string;
}

export interface SubKriteria{
    id:number;
    kriteria_id:number;
    nama:string;
    keterangan:string;
    bobot:number;
    errors:Array<any>;
    //sub_kriteria_id:Array<any>;
}


export interface ResponseRequest{
    data:any;
}


export interface Alternatif{
    id:number;
    nama:string;
    jenis_kelamin:string;
    alamat:string;
    //alternatif_id:Array<any>;
}

export interface Nilai{
    id:string;
    alternatif_id:string;
    kriteria_id:string;
    nilai:number;
}

export interface NilaiSubKriteria{
    id:string;
    nilai_id:string;
    sub_kriteria_id:string;
    nilai:number;
}

export interface NilaiAlternatif{
    id:string;
    nilai_id:string;
    alternatif_id:string;
    nilai:number;
}

export interface NilaiKriteria{
    id:string;
    nilai_id:string;
    kriteria_id:string;
    nilai:number;
}

export interface NilaiSubKriteriaAlternatif{
    id:string;
    nilai_id:string;
    sub_kriteria_id:string;
    alternatif_id:string;
    nilai:number;
}
