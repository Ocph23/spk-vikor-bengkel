export interface Kriteria{
    id:string;
    nama:string;
    variabel:string;
    keterangan:string;
    bobot:number;
    W:number;
    Max:number;
    Min:number;
}

export interface SubKriteria{
    id:string;
    kriteria_id:string;
    nama:string;
    keterangan:string;
    bobot:number;
  
    //sub_kriteria_id:Array<any>;
}
