export interface IUser {
    id: number;
    name: string;
    email: string;
    has_notes: number;
}

export interface IUserProps {
    users: {
        data: IUser[];
        meta: any;
        links: any;
    };
}
