@extends('layouts.app')
@section('title', 'Dashboard — ThinkSmart')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header -->
    <div class="flex items-start justify-between mb-8">
        <div>
            <h1 class="font-display font-700 text-3xl text-white tracking-tight">Command Center</h1>
            <p class="text-slate-500 text-sm font-mono mt-1">// {{ Auth::user()->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('quiz.index') }}" class="btn-violet px-4 py-2.5 rounded-xl font-semibold text-white text-sm flex items-center gap-2">
                🧠 My Quizzes
            </a>
            <a href="{{ route('summarize') }}" class="btn-primary px-4 py-2.5 rounded-xl font-semibold text-white text-sm flex items-center gap-2">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Summary
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">// Summary Stats</p>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['label'=>'Total Docs',  'value'=>$stats['total'],     'color'=>'text-brand-400',  'border'=>'border-brand-500/20',   'bg'=>'bg-brand-500/5'],
            ['label'=>'Completed',   'value'=>$stats['completed'], 'color'=>'text-emerald-400','border'=>'border-emerald-500/20','bg'=>'bg-emerald-500/5'],
            ['label'=>'Failed',      'value'=>$stats['failed'],    'color'=>'text-red-400',    'border'=>'border-red-500/20',    'bg'=>'bg-red-500/5'],
            ['label'=>'Processing',  'value'=>$stats['processing'],'color'=>'text-violet-400', 'border'=>'border-violet-500/20', 'bg'=>'bg-violet-500/5'],
        ] as $s)
        <div class="card-glass rounded-2xl p-5 {{ $s['border'] }} {{ $s['bg'] }} transition-all hover:scale-[1.02]">
            <div class="font-display font-700 text-4xl text-white mb-1">{{ $s['value'] }}</div>
            <div class="text-xs font-mono uppercase tracking-widest {{ $s['color'] }}">{{ $s['label'] }}</div>
        </div>
        @endforeach
    </div>

    <!-- Quiz Stats -->
    <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">// Quiz Stats</p>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        @foreach([
            ['label'=>'Quizzes Created','value'=>$quizStats['total'],                      'color'=>'text-violet-400', 'border'=>'border-violet-500/20','bg'=>'bg-violet-500/5'],
            ['label'=>'Total Attempts', 'value'=>$quizStats['attempts'],                   'color'=>'text-amber-400',  'border'=>'border-amber-500/20', 'bg'=>'bg-amber-500/5'],
            ['label'=>'Avg Score',      'value'=>round($quizStats['avg_score'] ?? 0).' pts','color'=>'text-brand-400', 'border'=>'border-brand-500/20', 'bg'=>'bg-brand-500/5'],
            ['label'=>'Best Score',     'value'=>($quizStats['best'] ?? 0).'%',            'color'=>'text-emerald-400','border'=>'border-emerald-500/20','bg'=>'bg-emerald-500/5'],
        ] as $s)
        <div class="card-glass rounded-2xl p-5 {{ $s['border'] }} {{ $s['bg'] }} transition-all hover:scale-[1.02]">
            <div class="font-display font-700 text-4xl text-white mb-1">{{ $s['value'] }}</div>
            <div class="text-xs font-mono uppercase tracking-widest {{ $s['color'] }}">{{ $s['label'] }}</div>
        </div>
        @endforeach
    </div>

    <!-- ── SUMMARY LOG TABLE ── -->
    <div class="card-glass rounded-2xl overflow-hidden mb-8">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-700/50">
            <h2 class="font-display font-600 text-white text-base flex items-center gap-2">
                📄 <span>Summary Log</span>
            </h2>
            <span class="text-xs font-mono text-slate-500">{{ $stats['total'] }} RECORDS</span>
        </div>

        @if($summaries->isEmpty())
        <div class="py-16 text-center">
            <div class="text-5xl mb-4 opacity-30">📂</div>
            <h3 class="font-display font-600 text-lg text-slate-300 mb-2">No summaries yet</h3>
            <p class="text-slate-500 text-sm mb-6">Upload your first document to get started</p>
            <a href="{{ route('summarize') }}" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold text-white inline-flex">Start Summarizing</a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-800">
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-brand-400 uppercase tracking-widest">Document</th>
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-brand-400 uppercase tracking-widest">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-brand-400 uppercase tracking-widest hidden sm:table-cell">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-brand-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-brand-400 uppercase tracking-widest hidden md:table-cell">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-mono font-semibold text-brand-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @foreach($summaries as $summary)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">{{ $summary->file_type_icon }}</span>
                                <span class="text-sm font-medium text-slate-200 truncate max-w-[180px]" title="{{ $summary->original_filename }}">
                                    {{ Str::limit($summary->original_filename, 30) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge-glow text-xs font-mono px-2 py-0.5 rounded-md">{{ strtoupper($summary->file_type) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500 font-mono hidden sm:table-cell">{{ $summary->file_size_formatted }}</td>
                        <td class="px-6 py-4">
                            @if($summary->status === 'completed')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> Done
                                </span>
                            @elseif($summary->status === 'processing')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-violet-500/10 text-violet-400 border border-violet-500/20">
                                    <span class="w-1.5 h-1.5 bg-violet-400 rounded-full animate-pulse"></span> Processing
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span> Failed
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-mono hidden md:table-cell">{{ $summary->created_at->format('M j, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                @if($summary->status === 'completed')
                                    <a href="{{ route('summarize.show', $summary) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-300 border border-slate-700 hover:border-slate-500 hover:text-white transition-all">View</a>
                                    <a href="{{ route('summarize.download', $summary) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-brand-400 border border-brand-500/30 hover:bg-brand-500/10 transition-all flex items-center gap-1">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                        .docx
                                    </a>
                                    <a href="{{ route('quiz.create', $summary) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-violet-400 border border-violet-500/30 hover:bg-violet-500/10 transition-all">🧠 Quiz</a>
                                @endif
                                <form method="POST" action="{{ route('summarize.destroy', $summary) }}" onsubmit="return confirm('Delete this summary?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-400 border border-red-500/20 hover:border-red-500/50 hover:bg-red-500/10 transition-all">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($summaries->hasPages())
        <div class="px-6 py-4 border-t border-slate-800">{{ $summaries->links() }}</div>
        @endif
        @endif
    </div>

    <!-- ── QUIZ LOG TABLE ── -->
    <div class="card-glass rounded-2xl overflow-hidden mb-8">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-700/50">
            <h2 class="font-display font-600 text-white text-base flex items-center gap-2">
                🧠 <span>Quiz Log</span>
            </h2>
            <div class="flex items-center gap-3">
                <span class="text-xs font-mono text-slate-500">{{ $quizStats['total'] }} QUIZZES</span>
                <a href="{{ route('quiz.index') }}" class="text-xs text-violet-400 hover:text-violet-300 font-mono transition-colors">View All →</a>
            </div>
        </div>

        @if($recentQuizzes->isEmpty())
        <div class="py-16 text-center">
            <div class="text-5xl mb-4 opacity-30">🧠</div>
            <h3 class="font-display font-600 text-lg text-slate-300 mb-2">No quizzes yet</h3>
            <p class="text-slate-500 text-sm">Summarize a document first, then generate a quiz from it</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-800">
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-violet-400 uppercase tracking-widest">Quiz Title</th>
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-violet-400 uppercase tracking-widest">Difficulty</th>
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-violet-400 uppercase tracking-widest">Questions</th>
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-violet-400 uppercase tracking-widest">Attempts</th>
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-violet-400 uppercase tracking-widest hidden md:table-cell">Best Score</th>
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-violet-400 uppercase tracking-widest hidden md:table-cell">Created</th>
                        <th class="px-6 py-3 text-right text-xs font-mono font-semibold text-violet-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @foreach($recentQuizzes as $quiz)
                    @php
                        $dc   = ['easy'=>'emerald','medium'=>'amber','hard'=>'red'];
                        $c    = $dc[$quiz->difficulty] ?? 'slate';
                        $best = $quiz->userAttempts()->latest()->first();
                        $cnt  = $quiz->userAttempts()->count();
                    @endphp
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-sm flex-shrink-0">🧠</div>
                                <div>
                                    <div class="text-sm font-medium text-slate-200 truncate max-w-[160px]" title="{{ $quiz->title }}">{{ Str::limit($quiz->title, 28) }}</div>
                                    <div class="text-xs text-slate-500 font-mono truncate max-w-[160px]">{{ Str::limit($quiz->summary->original_filename ?? '', 24) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-{{ $c }}-500/10 text-{{ $c }}-400 border border-{{ $c }}-500/20 capitalize">
                                {{ $quiz->difficulty }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-mono text-slate-300">{{ $quiz->total_questions }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-slate-300">{{ $cnt }}</td>
                        <td class="px-6 py-4 hidden md:table-cell">
                            @if($best)
                                @php $pct = $best->percentage; @endphp
                                <span class="text-sm font-bold font-mono {{ $pct >= 80 ? 'text-emerald-400' : ($pct >= 60 ? 'text-amber-400' : 'text-red-400') }}">
                                    {{ $pct }}%
                                </span>
                                <span class="text-xs text-slate-500 ml-1">({{ $best->score }}/{{ $best->total }})</span>
                            @else
                                <span class="text-xs text-slate-600">No attempts</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-mono hidden md:table-cell">{{ $quiz->created_at->format('M j, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('quiz.show', $quiz) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-violet-400 border border-violet-500/30 hover:bg-violet-500/10 transition-all">
                                    {{ $cnt > 0 ? 'Retake' : 'Start' }}
                                </a>
                                @if($best)
                                <a href="{{ route('quiz.result', $best) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-300 border border-slate-700 hover:border-slate-500 hover:text-white transition-all">
                                    Results
                                </a>
                                @endif
                                <form method="POST" action="{{ route('quiz.destroy', $quiz) }}" onsubmit="return confirm('Delete this quiz?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-400 border border-red-500/20 hover:border-red-500/50 hover:bg-red-500/10 transition-all">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- ── QUIZ ATTEMPT LOG TABLE ── -->
    <div class="card-glass rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-700/50">
            <h2 class="font-display font-600 text-white text-base flex items-center gap-2">
                📊 <span>Quiz Attempt Log</span>
            </h2>
            <span class="text-xs font-mono text-slate-500">{{ $quizStats['attempts'] }} ATTEMPTS</span>
        </div>

        @if($recentAttempts->isEmpty())
        <div class="py-16 text-center">
            <div class="text-5xl mb-4 opacity-30">📊</div>
            <h3 class="font-display font-600 text-lg text-slate-300 mb-2">No attempts yet</h3>
            <p class="text-slate-500 text-sm">Take a quiz to see your results here</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-800">
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-amber-400 uppercase tracking-widest">Quiz</th>
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-amber-400 uppercase tracking-widest">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-amber-400 uppercase tracking-widest">Grade</th>
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-amber-400 uppercase tracking-widest hidden sm:table-cell">Correct</th>
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-amber-400 uppercase tracking-widest hidden md:table-cell">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-mono font-semibold text-amber-400 uppercase tracking-widest hidden md:table-cell">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-mono font-semibold text-amber-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @foreach($recentAttempts as $attempt)
                    @php
                        $pct   = $attempt->percentage;
                        $grade = $attempt->grade;
                        $color = $pct >= 80 ? 'emerald' : ($pct >= 60 ? 'amber' : 'red');
                        $mins  = $attempt->time_taken ? floor($attempt->time_taken / 60) : 0;
                        $secs  = $attempt->time_taken ? $attempt->time_taken % 60 : 0;
                    @endphp
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-slate-200 truncate max-w-[160px]" title="{{ $attempt->quiz->title ?? '' }}">
                                {{ Str::limit($attempt->quiz->title ?? 'Quiz', 26) }}
                            </div>
                            <div class="text-xs text-slate-500 font-mono capitalize">{{ $attempt->quiz->difficulty ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-xl bg-{{ $color }}-500/10 border border-{{ $color }}-500/20 flex items-center justify-center">
                                    <span class="font-display font-700 text-sm text-{{ $color }}-400">{{ $pct }}%</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-sm font-display font-700 bg-{{ $color }}-500/10 text-{{ $color }}-400 border border-{{ $color }}-500/20">
                                {{ $grade }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-mono text-slate-300 hidden sm:table-cell">
                            {{ $attempt->score }}/{{ $attempt->total }}
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-slate-500 hidden md:table-cell">
                            {{ $attempt->time_taken ? sprintf('%d:%02d', $mins, $secs) : '—' }}
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-slate-500 hidden md:table-cell">
                            {{ $attempt->created_at->format('M j, Y') }}
                            <div class="text-slate-600">{{ $attempt->created_at->format('g:i A') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('quiz.result', $attempt) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-amber-400 border border-amber-500/30 hover:bg-amber-500/10 transition-all">Review</a>
                                <a href="{{ route('quiz.show', $attempt->quiz) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-violet-400 border border-violet-500/30 hover:bg-violet-500/10 transition-all">Retake</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection
