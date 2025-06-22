export enum NoteStatus {
    OPEN = 'open',
    CLOSED = 'closed',
    IN_PROGRESS = 'in_progress',
}

export enum NoteType {
    INFO = 'info',
    TASK = 'task',
    ALERT = 'alert',
}

export type NewForm = {
    merchantId: number;
    title: string;
    body: string;
    type: NoteType | '';
    status: NoteStatus | '';
};

export type EditForm = {
    title: string;
    body: string;
    type: NoteType | '';
    status: NoteStatus | '';
};

// Interface for a note
export interface Note {
    id: number;
    uid: string;
    title: string;
    body: string;
    status: NoteStatus;
    type: NoteType;
    assigned_to: string;
    created_by: string;
    updated_at: string;
    merchant: string;
}

export interface NoteList {
    notes: {
        data: Note[];
        meta: any;
        links: any;
    };
    creators: Creator[];
    filters: NoteFilters;
}

export interface NoteFilters {
    search?: string;
    type?: string;
    status?: string;
    creator?: number | string;
    start?: string;
    end?: string;
}

export interface Creator {
    id: number;
    name: string;
}
