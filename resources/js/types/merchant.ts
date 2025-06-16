export interface IMerchant {
    id: number;
    uid: string;
    name: string;
    phone?: string;
    email?: string;
    category?: string;
    has_notes: number;
}

export interface IMerchantProps {
    merchants: {
        data: IMerchant[];
        meta: any;
        links: any;
    };
}
