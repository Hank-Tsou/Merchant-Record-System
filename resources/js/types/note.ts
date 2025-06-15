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

// Interface for a note
export interface Note {
    id: number;
    title: string;
    body: string;
    status: NoteStatus;
    type: NoteType;
    created_by: string;
    assigned_to?: string;
    merchant_id?: number;
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
